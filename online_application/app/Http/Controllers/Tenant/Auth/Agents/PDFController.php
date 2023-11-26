<?php

namespace App\Http\Controllers\Tenant;

use PDF;
use Auth;
use Mail;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Submission;
use App\Http\Controllers\Controller;

class PDFController extends Controller
{
    public function pdf(Submission $submission, $action)
    {
        $application = $submission->application()->with('sections.fields')->first();

        if ($action == 'view') {
            $html = view('back.students.submission-pdf', compact('submission', 'application'))->render();

            return PDF::loadHTML($html)->stream();
        }

        if ($action == 'download') {
            $name = Str::slug($submission->student->name).'-'.$application->slug.'-'.time().'.pdf';

            return PDF::loadView('back.students.submission-pdf', ['submission'=> $submission, 'application' => $application])->download($name);
        }

        if ($action == 'save' || $action == 'email') {
            $name = Str::slug($submission->student->name).'-'.$application->slug.'-'.time().'.pdf';

            $html = view('back.students.submission-pdf', compact('submission', 'application'))->render();
            $html = PDF::loadHTML($html)->output();
            $path = session('tenant').'/'.$name;

            if (Storage::put($path, $html)) {
                return Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addMinutes(5));
                /*$message = "New Application Submitted click here to download the PDF file " . Storage::disk('s3')->temporaryUrl($path);
                mail('mattalah@higher-education-marketing.com , archie@higher-education-marketing.com' , 'New Application submission' , $message );*/
            }
        }
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
}
