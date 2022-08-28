<?php

namespace App\Exports;

use App\Models\Prisoner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PrisonersExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function headings(): array
    {
        return [
            'name_and_father_name', 'arabic_name', 'iqama_no', 'passport_no', 'detention_authority', 'region', 'detention_city',
            'prison', 'gender', 'cnic', 'hijri_detention_date', 'gregorian_detention_date', 'case_details', 'sentence_in_years',
            'sentence_in_months', 'financial_claim', 'penalty_fine', 'case_court_name', 'case_city', 'case_number', 'case_prisoner_number',
            'case_claim_number', 'case_sadad_number', 'case_claimer_name', 'case_claimer_contact_number', 'case_consular_access_date',
            'etd_issuance_date', 'etd_number', 'case_closed', 'case_closing_reason', 'case_closing_date_hijri', 'case_closing_date_gg',
            'date_of_birth', 'provinces', 'district', 'tehseel', 'muhallah_town', 'contact_no_in_pakistan', 'detention_period',
            'expected_release_date', 'status'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        return QueryBuilder::for(Prisoner::class)
            ->allowedIncludes(['prisoner_charges'])
            ->with('prisoner_charges')
            ->allowedFilters([
                AllowedFilter::scope('search_charges'),
                AllowedFilter::scope('search_string'),
                AllowedFilter::scope('search_date'),
                AllowedFilter::scope('search_released'),
                AllowedFilter::scope('search_expected'),
                AllowedFilter::scope('search_from'),
                AllowedFilter::exact('cnic'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('detention_authority'),
                AllowedFilter::exact('region'),
                AllowedFilter::exact('detention_city'),
                AllowedFilter::exact('prison'),
                AllowedFilter::exact('passport_no'),
                AllowedFilter::exact('case_closing_reason'),
                AllowedFilter::exact('case_closed'),
                AllowedFilter::exact('iqama_no')
            ])->latest()->get(['name_and_father_name', 'arabic_name', 'iqama_no', 'passport_no', 'detention_authority', 'region', 'detention_city',
                'prison', 'gender', 'cnic', 'hijri_detention_date', 'gregorian_detention_date', 'case_details', 'sentence_in_years',
                'sentence_in_months', 'financial_claim', 'penalty_fine', 'case_court_name', 'case_city', 'case_number', 'case_prisoner_number',
                'case_claim_number', 'case_sadad_number', 'case_claimer_name', 'case_claimer_contact_number', 'case_consular_access_date',
                'etd_issuance_date', 'etd_number', 'case_closed', 'case_closing_reason', 'case_closing_date_hijri', 'case_closing_date_gg',
                'date_of_birth', 'provinces', 'district', 'tehseel', 'muhallah_town', 'contact_no_in_pakistan', 'detention_period',
                'expected_release_date', 'status',]);
    }


}
