<?php

namespace App\Http\Controllers\Tenant\School\Instructor;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $campusRepository;

    public function __construct(CampusRepositoryInterface $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }

    public function index(Request $request)
    {
        $campuses = $this->campusRepository->all();

        return view('front.instructor.calendar', compact('campuses'));
    }
}
