<?php

namespace App\Http\Controllers\Tenant;

use PDF;
use Auth;
use Mail;
use Storage;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Student;
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\Settings;
use \PhpOffice\PhpWord\IOFactory;
use App\Tenant\Models\Submission;
use App\Http\Controllers\Controller;
use \PhpOffice\PhpWord\TemplateProcessor;
use \setasign\Fpdi\PdfParser\StreamReader;
use App\Helpers\Application\SubmissionHelpers;
use App\Tenant\Models\Application;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Symfony\Component\HttpFoundation\File\File;

class PDFController extends Controller
{
    public function pdf(Submission $submission, $action , $signedInLink = false)
    {
        $application = $submission->application()->with('sections.fields')->first();

        $pdf = $this->generateApplicationPDF($submission, $application);

        $name = $this->constructFileName($submission, $application);

        switch ($action) {
            case 'save':
            case 'download':
                $pdf->Output($name, 'D');
                break;
            case 'save':
            case 'email':
                $pdf->Output(storage_path($name), 'F');
                $content = file_get_contents(storage_path($name));
                unlink(storage_path($name));
                $path = session('tenant').'/'.$name;
                if (Storage::put($path, $content)) {

                        if($signedInLink){

                            return Storage::disk('s3')->temporaryUrl($path , Carbon::now()->addMinutes(10));

                        }else{
                            return env('APP_URL') . '/submissions/applicants/files/view?fileName='. $path;
                        }

                }
                break;
            default:
                return $pdf->Output();
                break;
        }
    }


    protected function constructFileName($submission, $application)
    {
        $programFieldNames = $application->getProgramFieldName(true , true);

        $programValue = SubmissionHelpers::exctractProgramValueFromSubmission($programFieldNames , $submission);

        if(isset($programValue)){
            $slug = Str::slug($programValue);
        }else{
            $slug = $application->slug;
        }
        $name = Str::slug($submission->student->name).'-'.$slug.'-'.time().'.pdf';
        return $name;
    }

    public function emailPDF()
    {
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        $mm = new Mail_mime("\n");

        $mm->setTxtBody($body);
        $mm->addAttachment($output, 'application/pdf', 'output.pdf', false);

        $body = $mm->get();
        $headers = $mm->headers(['From'=>$from, 'Subject'=>$subject]);

        $mail = &Mail::factory('mail');
        if ($mail->send($to, $headers, $body)) {
            echo 'Your message has been sent.';
        }
    }

    private function generateApplicationPDF($submission, $application)
    {


        $student = Student::find($submission->student_id);
        $student_files = $student->files;


        $mpdf = new Mpdf();
        $pdfs = [];
        $applicationPdf = view('back.students.submission-pdf', compact('submission', 'application', 'student_files'))->render();

        $mpdf->WriteHTML($applicationPdf);

        foreach ($student_files as $file) {

            $headers = get_headers(Storage::disk('s3')->temporaryUrl($file->name , Carbon::now()->addMinutes(10)), 1);

            if (strpos($headers['Content-Type'], 'application/pdf') !== false) {
                $fileContent = file_get_contents(Storage::disk('s3')->temporaryUrl($file->name,
                Carbon::now()->addMinutes(10)));
                try {
                    $pagecount = $mpdf->SetSourceFile(StreamReader::createByString($fileContent));
                    for ($i = 1; $i <= $pagecount; $i++) {
                        $tplId = $mpdf->importPage($i);
                        $mpdf->AddPage();
                        $mpdf->useTemplate($tplId, 5, 5, 200);
                    }
                } catch(\Exception $e) {
                    $pdfs[Storage::disk('s3')->temporaryUrl($file->name, Carbon::now()->addMinutes(10))] =
                    $file->original_name;
                }
            }elseif( strpos($headers['Content-Type'], 'vnd.openxmlformats-officedocument.wordprocessingml.document') !== false){

            } elseif(strpos($headers['Content-Type'], 'application/msword') !== false ) {

                try {
                    $domPdfPath = base_path('vendor/dompdf/dompdf');
                    \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
                    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
                    $timestamp = Carbon::now()->timestamp;
                    $content = file_get_contents(Storage::disk('s3')->temporaryUrl($file->name,
                    Carbon::now()->addMinutes(10)));

                    file_put_contents(storage_path($file->original_name), $content);
                    $fileName = $file->original_name;

                    $wordContent = \PhpOffice\PhpWord\IOFactory::load(storage_path($file->original_name), 'MsDoc');
                    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordContent , 'PDF');
                    $timestamp = Carbon::now()->timestamp;
                    $pdfLocation = storage_path("doc-to-pdf-$timestamp.pdf");
                    $xmlWriter->save($pdfLocation, true);
                    $file = file_get_contents(storage_path("doc-to-pdf-$timestamp.pdf"));
                    $pagecount = $mpdf->SetSourceFile(StreamReader::createByString($file));
                    for ($i = 1; $i <= $pagecount; $i++) {
                        $tplId = $mpdf->importPage($i);
                        $mpdf->AddPage();
                        $mpdf->useTemplate($tplId, 5, 5, 200);
                    }
                    unlink(storage_path($fileName));
                    unlink(storage_path("doc-to-pdf-$timestamp.pdf"));
                } catch (\Exception $e) {
                    $pdfs[Storage::disk('s3')->temporaryUrl($file->name, Carbon::now()->addMinutes(10))] =
                    $file->original_name;
                }


            } elseif(strpos($headers['Content-Type'], 'application/') !== false ) {
                try {
                    $fileName = $file->original_name;
                    $content = file_get_contents(Storage::disk('s3')->temporaryUrl($file->name,
                    Carbon::now()->addMinutes(10)));
                    file_put_contents(storage_path($fileName), $content);
                    $domPdfPath = base_path( 'vendor/dompdf/dompdf');
                    \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
                    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
                    $phpWord = \PhpOffice\PhpWord\IOFactory::load(storage_path($fileName));
                    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
                    $timestamp = Carbon::now()->timestamp;
                    $pdfLocation = storage_path("doc-to-pdf-$timestamp.pdf");
                    $xmlWriter->save($pdfLocation, true);
                    $file = file_get_contents(storage_path("doc-to-pdf-$timestamp.pdf"));
                    $pagecount = $mpdf->SetSourceFile(StreamReader::createByString($file));
                    for ($i = 1; $i <= $pagecount; $i++) {
                        $tplId = $mpdf->importPage($i);
                        $mpdf->AddPage();
                        $mpdf->useTemplate($tplId, 5, 5, 200);
                    }
                    unlink(storage_path($fileName));
                    unlink(storage_path("doc-to-pdf-$timestamp.pdf"));
                } catch (\Exception $e) {
                    $pdfs[Storage::disk('s3')->temporaryUrl($file->name, Carbon::now()->addMinutes(10))] =
                    $file->original_name;
                }
            }
        }

        if(!empty($pdfs)) {
            $mpdf->AddPage();
            $html = "<html><head></head><body> Invalide files compressions : </body></html>";
            $mpdf->WriteHTML($html);
            foreach ($pdfs as $link => $pdf) {
                $html = "<html><head></head><body> $pdf : <br><a href='$link' target='_blank'>$link</a> </body></html>";
                $mpdf->WriteHTML($html);
            }
        }

        return $mpdf;
    }

    public function generateEmptyApplicationPDF(Application $application, $action) {
        $mpdf = new Mpdf();
        $pdfs = [];
        $applicationPdf = view('back.applications.application-pdf', compact('application'))->render();
        $mpdf->WriteHTML($applicationPdf);

        if(!empty($pdfs)) {
            $mpdf->AddPage();
            $html = "<html><head></head><body> Invalide files compressions : </body></html>";
            $mpdf->WriteHTML($html);
            foreach ($pdfs as $link => $pdf) {
                $html = "<html><head></head><body> $pdf : <br><a href='$link' target='_blank'>$link</a> </body></html>";
                $mpdf->WriteHTML($html);
            }
        }
        $name = $application->title.'.pdf';

        switch ($action) {
            case 'save':
            case 'download':
                $mpdf->Output($name, 'D');
                break;
            default:
                return $mpdf->Output();
                break;
        }
    }
}
