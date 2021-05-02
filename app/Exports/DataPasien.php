<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataPasien implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $data = DB::table('list_of_payments')
            ->join('check_up_results', 'list_of_payments.check_up_result_id', '=', 'check_up_results.id')

            ->join('list_of_payment_items', 'check_up_results.id', '=', 'list_of_payment_items.check_up_result_id')
            ->join('detail_item_patients', 'list_of_payment_items.detail_item_patient_id', '=', 'detail_item_patients.id')
            ->join('price_items', 'detail_item_patients.price_item_id', '=', 'price_items.id')

            ->join('list_of_payment_services', 'check_up_results.id', '=', 'list_of_payment_services.check_up_result_id')
            ->join('detail_service_patients', 'list_of_payment_services.detail_service_patient_id', '=', 'detail_service_patients.id')
            ->join('price_services', 'detail_service_patients.price_service_id', '=', 'price_services.id')

            ->join('users', 'check_up_results.user_id', '=', 'users.id')
            ->join('branches', 'users.branch_id', '=', 'branches.id')
            ->join('registrations', 'check_up_results.patient_registration_id', '=', 'registrations.id')
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'list_of_payments.id as list_of_payment_id', 'check_up_results.id as check_up_result_id', 'registrations.id_number as registration_number',
                'patients.id_member as patient_number', 'patients.pet_category', 'patients.pet_name', 'registrations.complaint',

                DB::raw("TRIM(SUM(detail_item_patients.price_overall) + SUM(detail_service_patients.price_overall))+0 as price_overall"),
                DB::raw("TRIM(SUM(price_items.capital_price * detail_item_patients.quantity) + SUM(price_services.capital_price * detail_service_patients.quantity))+0 as capital_price"),
                DB::raw("TRIM(SUM(price_items.doctor_fee * detail_item_patients.quantity) + SUM(price_services.doctor_fee * detail_service_patients.quantity))+0 as doctor_fee"),
                DB::raw("TRIM(SUM(price_items.petshop_fee * detail_item_patients.quantity) + SUM(price_services.petshop_fee * detail_service_patients.quantity))+0 as petshop_fee"),

                'users.fullname as created_by', DB::raw("DATE_FORMAT(list_of_payments.created_at, '%d %b %Y') as created_at"),
                DB::raw('(CASE WHEN check_up_results.status_outpatient_inpatient = 1 THEN "Rawat Inap" ELSE "Rawat Jalan" END) AS status_outpatient_inpatient'))
            ->groupBy('list_of_payments.id', 'check_up_results.id', 'registrations.id_number', 'patients.id_member', 'patients.pet_category', 'patients.pet_name',
                'registrations.complaint', 'users.fullname', 'list_of_payments.created_at', 'check_up_results.status_outpatient_inpatient')
            ->get();

        return $data;
    }

    public function headings(): array
    {
        return [
            ['No.', 'No. Registrasi', 'No. Pasien', 'Jenis Hewan', 'Nama Hewan', 'Keluhan', 'Perawatan', 'Total Keseluruhan',
                'Harga Modal Keseluruhan', 'Fee Dokter Keseluruhan', 'Fee Petshop Keseluruhan', 'Dibuat Oleh', 'Tanggal Dibuat'],
        ];
    }

    public function title(): string
    {
        return 'Laporan Keuangan Harian';
    }

    public function map($list_of_payments): array
    {
        return [
            $list_of_payments->rownum,
            $list_of_payments->registration_number,
            $list_of_payments->patient_number,
            $list_of_payments->pet_category,
            $list_of_payments->pet_name,
            $list_of_payments->complaint,
            $list_of_payments->status_outpatient_inpatient,
            $list_of_payments->price_overall,
            $list_of_payments->capital_price,
            $list_of_payments->doctor_fee,
            $list_of_payments->petshop_fee,
            $list_of_payments->created_by,
            $list_of_payments->created_at,
        ];
    }
}
