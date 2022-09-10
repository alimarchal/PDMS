<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prisoner extends Model
{
    use HasFactory;

    public function scopeSearchString(Builder $query, $search): Builder
    {
        return $query->where('name_and_father_name', 'LIKE', '%' . $search . '%')->
        orWhere('arabic_name', 'LIKE', '%' . $search . '%')->
        orWhere('iqama_no', 'LIKE', '%' . $search . '%')->
        orWhere('passport_no', 'LIKE', '%' . $search . '%')->
        orWhere('detention_authority', 'LIKE', '%' . $search . '%')->
        orWhere('region', 'LIKE', '%' . $search . '%')->
        orWhere('detention_city', 'LIKE', '%' . $search . '%')->
        orWhere('prison', 'LIKE', '%' . $search . '%')->
        orWhere('district', 'LIKE', '%' . $search . '%')->
        orWhere('tehseel', 'LIKE', '%' . $search . '%')->
        orWhere('case_court_name', 'LIKE', '%' . $search . '%');
    }


    public function scopeSearchCharges(Builder $query, $search): Builder
    {
        return $query->whereRelation('prisoner_charges','crime_charges',$search);
    }


    public function scopeSearchDate(Builder $query, $search): Builder
    {
        $dateS = Carbon::now()->subMonth(3);
        $dateE = Carbon::now();
        return $query->where('case_closing_reason', '!=', 'Deported')->whereBetween('gregorian_detention_date', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')]);
    }

    //search_from
    public function scopeSearchFrom(Builder $query, $search): Builder
    {
        $datetime1 = null;
        $datetime2 = null;


        if (isset($search) && !empty($search)) {
            $dates = explode(' – ', $search);
            $fdate = @$dates[0];
            $tdate = @$dates[1];
            if (!empty($fdate) && !empty($tdate)) {
                $datetime1 = new \DateTime($fdate);
                $datetime2 = new \DateTime($tdate);
            }
        }

        $date_from = null;
        $date_to = null;

        if (!empty($search)) {
            $date_from = $datetime1->format('Y-m-d');
            $date_to = $datetime2->format('Y-m-d');
        }


        return $query->whereBetween('gregorian_detention_date', [$date_from, $date_to])->orWhereBetween('case_closing_date_gg', [$date_from, $date_to]);
    }

    public function scopeSearchReleased(Builder $query, $search): Builder
    {
        $dateS = Carbon::now()->subMonth(3);
        $dateE = Carbon::now();
        return $query->where('status', 'Released')->orWhere('prisoners.case_closing_reason', 'Deported')->
        whereBetween('case_closing_date_gg', [$dateS->format('Y-m-d 00:00:00'), $dateE->format('Y-m-d 23:59:59')]);
    }

    public function scopeSearchExpected(Builder $query, $search): Builder
    {
        $dateS = Carbon::now()->addMonth(3);
        $dateE = Carbon::now();

        return $query->where('case_closing_reason', '!=', 'Deported')->whereBetween('expected_release_date', [$dateE->format('Y-m-d'), $dateS->format('Y-m-d')]);
    }


    public $fillable = [
        'name_and_father_name',
        'arabic_name',
        'iqama_no',
        'passport_no',
        'detention_authority',
        'region',
        'detention_city',
        'prison',
        'gender',
        'cnic',
        'hijri_detention_date',
        'gregorian_detention_date',
        'crime_charges',
        'case_details',
        'sentence_in_years',
        'sentence_in_months',
        'financial_claim',
        'penalty_fine',
        'case_court_name',
        'case_city',
        'case_number',
        'case_prisoner_number',
        'case_claim_number',
        'case_sadad_number',
        'case_claimer_name',
        'case_claimer_contact_number',
        'case_consular_access_date',
        'etd_issuance_date',
        'etd_number',
        'case_closed',
        'case_closing_date_hijri',
        'date_of_birth',
        'provinces',
        'district',
        'tehseel',
        'muhallah_town',
        'contact_no_in_pakistan',
        'detention_period',
        'expected_release_date',
        'case_closing_date_gg',
        'case_closed',
        'case_closing_reason',
        'attachment',
        'photo',
        'passport',
        'iqama',
        'other',
        'status',
        'detention_place',
        'all_charges',
    ];

    public function shifted_to_other_department()
    {
        return $this->hasMany(PrisionerShifted::class);
    }

    public static function detention_authority(): array
    {
        return [
            'Police' => 'الشرطة',
            'Anti-Narcotics' => 'مكافحة المخدرات',
            'Border Security' => 'أمن الحدود',
            'Intelligence - State Security' => 'المباحث - أمن الدولة',
            'Administrative intelligence' => 'المباحث الإدارية',
            'Public Prosecution' => 'النيابة العامة',
        ];
    }

    public static function detention_city(): array
    {
        return [
            'Ahsa' => 'الأحساء',
            'Ahad Rafeeda' => 'أحد رفيدة',
            'Adham' => 'أضم',
            'Ahad Masarha' => 'أحد المسارحة',
            'Abha' => 'أبها',
            'Abu Areesh' => 'أبوعريش',
            'Aflaj' => 'الأفلاج',
            'Afeef' => 'عفيف',
            'Adeed' => 'العديد',
            'Amlaj' => 'أملج',
            'Aqeeq' => 'العقيق',
            'Aqla Suqoor' => 'عقلة الصقور',
            'Arar' => 'عرعر',
            'Ardhiyat' => 'العرضيات',
            'Aridha' => 'العارضة',
            'Aseer' => 'منطقة عسير',
            'Asyah' => 'الأسياح',
            'Bada' => 'البدع',
            'Badaae' => 'البدائع',
            'Badr Al Janoob' => 'بدر الجنوب',
            'Badr' => 'بدر',
            'Baha' => 'الباحة',
            'Bahra' => 'بحره',
            'Baljurashi' => 'بلجرشي',
            'Balqarn' => 'بلقرن',
            'Bani Hasan' => 'بني حسن',
            'Baqaa' => 'بقعاء',
            'Baqaiq' => 'بقيق',
            'Bariq' => 'بارق',
            'Bark' => 'البرك',
            'Beesh' => 'بيش',
            'Bisha' => 'بيشة',
            'Bukairiya' => 'البكيرية',
            'Buraidah' => 'بريدة',
            'Dair' => 'الدائر',
            'Dammam' => 'الدمام',
            'Darb' => 'الدرب',
            'Dariya' => 'الدرعية',
            'Dawadmi' => 'الدوادمي',
            'Dhaba' => 'ضبا',
            'Dhahran Al Janoob' => 'ظهران الجنوب',
            'Dhamad' => 'ضمد',
            'Dhariya' => 'ضرية',
            'Dharma' => 'ضرما',
            'Duma Jandal' => 'دومة الجندل',
            'Edabi' => 'العيدابي',
            'Ees' => 'العيص',
            'Faifa' => 'فيفاء',
            'Ghaat' => 'الغاط',
            'Ghamid Zanad' => 'غامد الزناد',
            'Ghazala' => 'الغزالة',
            'Habuna' => 'حبونا',
            'Hafr Al Batin' => 'حفر الباطن',
            'Hail' => 'حائل',
            'Hait' => 'الحائط',
            'Hajra' => 'الحجرة',
            'Hanakiya' => 'الحناكية',
            'Haqal' => 'حقل',
            'Harth' => 'الحرث',
            'Hareeq' => 'الحريق',
            'Harja' => 'الحرجة',
            'Hota Bani Tamim' => 'حوطة بني تميم',
            'Huraimla' => 'حريملاء',
            'Huroob' => 'هروب',
            'Jaof' => 'منطقة الجوف',
            'Jazan' => 'جازان',
            'Jiddah' => 'جده',
            'Jubail' => 'الجبيل',
            'Jumoom' => 'الجموم',
            'Juzur Fursan' => 'جزر فرسان',
            'Kamil' => 'الكامل',
            'Khabash' => 'خباش',
            'Khafji' => 'الخفجي',
            'Khaibar' => 'خيبر',
            'Khamees Mashet' => 'خميس مشيط',
            'Kharj' => 'الخرج',
            'Kharkheer' => 'الخرخير',
            'Kharma' => 'الخرمة',
            'Khubar' => 'الخبر',
            'Khulais' => 'خليص',
            'Laith الليث' => '',
            'Madinah' => 'المدينة المنورة',
            'Mahd Dhahab' => 'مهد الذهب',
            'Majarda' => 'المجاردة',
            'Majma' => 'المجمعة',
            'Makkah' => 'مكة المكرمة',
            'Mandaq' => 'المندق',
            'Marat' => 'مرات',
            'Mazahmiya' => 'المزاحمية',
            'Mesaan' => 'ميسان',
            'Mikhwah' => 'المخواة',
            'Miznab' => 'المذنب',
            'Moqiq' => 'موقق',
            'Muhayel Aseer' => 'محايل عسير',
            'Muwaih' => 'المويه',
            'Nabhaniya' => 'النبهانية',
            'Najran' => 'نجران',
            'Namas' => 'النماص',
            'Northern borders' => 'منطقة الحدود الشمالية',
            'Nuairiya' => 'النعيرية',
            'Ola' => 'العلا',
            'Owaiqila' => 'العويقيلة',
            'Oyun Al Jawa' => 'عيون الجواء',
            'Qarya Olaya' => 'قرية العليا',
            'Qaseem' => 'منطقة القصيم',
            'Qateef' => 'القطيف',
            'Qulwa' => 'قلوة',
            'Qunfutha' => 'القنفذة',
            'Qura' => 'القرى',
            'Qurayat' => 'القريات',
            'Quweiya' => 'القويعية',
            'Rabigh' => 'رابغ',
            'Rafha' => 'رفحاء',
            'Rain' => 'الرين',
            'Raith' => 'الريث',
            'Rejal Alma' => 'رجال ألمع',
            'Ranya' => 'رنية',
            'Ras Tanura' => 'رأس التنورة',
            'Rass' => 'الرس',
            'Rimah' => 'الرماح',
            'Riyadh Al Khabra' => 'رياض الخبراء',
            'Riyadh' => 'الرياض',
            'Saar' => 'ثار',
            'Sabya' => 'صبيا',
            'Samita' => 'صامطة',
            'Saraat Obaida' => 'سراة عبيدة',
            'Shamasiya' => 'الشماسية',
            'Shamli' => '',
            'Shanan' => '',
            'Shaqraa' => '',
            'Sharura' => '',
            'Skaka' => '',
            'Slaimi' => '',
            'Sulail' => '',
            'Sumairaa' => '',
            'Tabarjal' => '',
            'Tabuk' => '',
            'Taif' => '',
            'Taima' => '',
            'Tanuma' => '',
            'Taraib' => '',
            'Taslees' => '',
            'Thadiq' => '',
            'Tiwal' => '',
            'Turaif' => '',
            'Turba' => '',
            'Unaiza' => '',
            'Wadi Dawasar' => '',
            'Wadi Fara' => '',
            'Wajah' => '',
            'Yadma' => '',
            'Yambo' => '',
            'Zulfi' => '',
        ];
    }

    public static function prisoner_status(): array
    {
        return [
            'Detainee',
            'Undertrial',
            'Sentenced',
            'Death Sentenced',
        ];
    }


    public static function crime_charges(): array
    {
        return [
            'Drugs' => 'مخدرات',
            'Theft' => 'سرقة',
            'Robbery' => 'سلب',
            'Financial Claim' => 'مطالبة مالية',
            'Border Security Violation' => 'مخالفة نظام الحدود',
            'Bribery' => 'رشوة',
            'Embezzlement' => 'خيانة أمانة/اختلاس',
            'Fighting' => 'مضاربة',
            'Forgery' => 'تزوير',
            'Fraud' => 'نصب واحتيال',
            'Immoral offence' => 'قضية أخلاقية',
            'Kidnapping' => 'الخطف',
            'Miscellaneous' => 'متفرق',
            'Murder' => 'قتل',
            'Wine' => 'شرب/ ترويج الخمور',
            'Smuggling' => 'تهريب',
            'Security case' => 'قضية أمنية',
            'Traffic Accident' => 'حادث مروري',
            'Money Laundering' => 'غسيل الأموال',
        ];
    }


    public static function prisons(): array
    {
        return [
            'Ahsa' => 'الأحساء',
            'Arar' => 'عرعر',
            'Askaan' => 'اسكان',
            'Buraidah' => 'بريدة',
            'Dammam' => 'الدمام',
            'Dammam - (Intelligence jail)' => 'الدمام - سجن المباحث',
            'Duwadmi' => 'الدوادمي',
            'Hafr al Batin' => 'حفر الباطن',
            'Hail' => 'حائل',
            'Hair' => 'الحائر',
            'Hota bani tamim' => 'حوطة بني تميم',
            'Jubail' => 'الجبيل',
            'Khafji' => 'الخفجي',
            'Kharj' => 'الخرج',
            'Majma' => 'المجمعة',
            'Malaz' => 'الملز',
            'Muzahmiyah' => 'المزاحمية',
            'Qurayyat' => 'القريات',
            'Quwaiiyah' => 'القويعية',
            'Rafha' => 'رفحاء',
            'Rass' => 'الرس',
            'Sakaka' => 'سكاكا',
            'Shaqra' => 'شقراء',
            'Tabuk' => 'تبوك',
            'Turaif' => 'طريف',
            'Unaizah' => 'عنيزة',
            'Wadi Dawasir' => 'وادي الدواسر',
        ];
    }

    public static function regions(): array
    {
        return [
            'Eastern' => 'الشرقية',
            'Northern' => 'الشمالية',
            'Riyadh' => 'الرياض',
            'Qaseem' => 'القصيم',
            'Hail' => 'حائل',
            'AlJaof' => 'الجوف',
            'Tabuk' => 'تبوك',
        ];
    }


    public function prisoner_charges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrisonerCharges::class);
    }


    public function prisoner_shifting(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrisionerShifted::class);
    }

    public function assistance(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Assistance::class);
    }

    public static function courts(): array
    {
        return [
            'General court' => 'المحكمة العامة',
            'Criminal court' => 'المحكمة الجزائية',
            'Tanfeez Court' => 'محكمة التنفيذ',
        ];
    }


    public static function provinces(): array
    {
        return [
            'Punjab',
            'Sindh',
            'Khyber Pakhtunkhwa',
            'Balochistan',
            'Islamabad',
            'FATA',
            'Gilgit Biltistan',
            'Azad Jammu and Kashmir',
        ];
    }


    public static function districts(): array
    {
        return [
            'Abbottabad',
            'Astore',
            'Attock',
            'Awaran',
            'Badin',
            'Bagh',
            'Bahawalnagar',
            'Bahawalpur',
            'Bajaur',
            'Bannu',
            'Barkhan',
            'Batagram',
            'Bhakkar',
            'Bhimber',
            'Buner',
            'Chagai',
            'Chakwal',
            'Charsadda',
            'Chiniot',
            'Dadu',
            'Darel',
            'Dera Bugti',
            'Dera Ghazi Khan',
            'Dera Ismail Khan',
            'Diamer',
            'Duki',
            'Faisalabad',
            'Ghanche',
            'Ghizer',
            'Ghotki',
            'Gilgit',
            'Gujranwala',
            'Gujrat',
            'Gupis Yasin',
            'Gwadar',
            'Hafizabad',
            'Hangu',
            'Haripur',
            'Harnai',
            'Hattian',
            'Haveli',
            'Hunza',
            'Hyderabad',
            'Islamabad',
            'Jacobabad',
            'Jafarabad',
            'Jamshoro',
            'Jhal Magsi',
            'Jhang',
            'Jhelum',
            'Kachhi',
            'Kalat',
            'Karachi Central',
            'Karachi East',
            'Karachi South',
            'Karachi West',
            'Karak',
            'Kashmore ',
            'Kasur',
            'Kech',
            'Khairpur',
            'Khanewal',
            'Kharan',
            'Kharmang',
            'Khushab',
            'Khuzdar',
            'Khyber',
            'Killa Abdullah',
            'Kohat',
            'Kohlu',
            'Kolai Pallas',
            'Korangi',
            'Kotli',
            'Kurram',
            'Lahore',
            'Lakki Marwat',
            'Larkana',
            'Lasbela',
            'Layyah',
            'Lodhran',
            'Loralai',
            'Lower Chitral',
            'Lower Dir',
            'Lower Kohistan',
            'Malakand',
            'Malir',
            'Mandi Bahauddin',
            'Mansehra',
            'Mardan',
            'Mastung',
            'Matiari',
            'Mianwali',
            'Mirpur Khas',
            'Mirpur',
            'Mohmand',
            'Multan',
            'Musakhel',
            'Muzaffarabad',
            'Muzaffargarh',
            'Nagar',
            'Nankana Sahib',
            'Narowal',
            'Naseerabad',
            'Naushahro Firoze',
            'Neelum',
            'North Waziristan',
            'Nowshera',
            'Nushki',
            'Okara',
            'Orakzai',
            'Pakpattan',
            'Panjgur',
            'Peshawar',
            'Pishin',
            'Poonch',
            'Qambar Shahdadkot',
            'Qilla Saifullah',
            'Quetta',
            'Rahim Yar Khan',
            'Rajanpur',
            'Rawalpindi',
            'Roundu',
            'Sahiwal',
            'Sanghar',
            'Sargodha',
            'Shaheed Benazirabad',
            'Shaheed Sikandarabad',
            'Shangla',
            'Sheikhupura',
            'Sherani',
            'Shigar',
            'Shikarpur',
            'Sialkot',
            'Sibi',
            'Skardu',
            'Sohbatpur',
            'South Waziristan',
            'Sudhnutti',
            'Sujawal',
            'Sukkur',
            'Swabi',
            'Swat',
            'Tando Allahyar',
            'Tando Muhammad Khan',
            'Tangir',
            'Tank',
            'Tharparkar',
            'Thatta',
            'Toba Tek Singh',
            'Tor Ghar',
            'Umerkot',
            'Upper Chitral',
            'Upper Dir',
            'Upper Kohistan',
            'Vehari',
            'Washuk',
            'Zhob',
            'Ziarat',
        ];
    }
}
