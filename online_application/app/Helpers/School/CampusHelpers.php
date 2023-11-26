<?php

namespace App\Helpers\School;

use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Tenant\Models\Campus;
use Illuminate\Support\Arr;

class CampusHelpers
{
    protected $campusRepository;

    public function __construct(CampusRepositoryInterface $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }

    public static function getCampusesInArrayOnlyTitleId($array = null)
    {
        if ($array == null) {
            $array = Campus::all('title', 'id')->toArray();
        }

        return ModelHelpers::arrayToArrayOnlyWithTitleId($array);
    }

    public static function getCampusesListWithSlug($array = null)
    {
        $list = Campus::pluck('title', 'slug')->all();
        return $list;
    }

    public static function getProgramInArrayOnlyTitleId()
    {
        return Arr::pluck(Campus::all()->toArray(), 'title', 'id');
    }

    public function all($columns = false)
    {
        return $this->campusRepository->all();
    }

}
