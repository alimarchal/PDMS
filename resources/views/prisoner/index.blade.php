<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
        <style>
            @media screen {
                #hideOnScreen {
                    display: none;
                    width: 1200px;
                    background-color: white;
                    padding: 20px;
                    /*display: block;*/
                    border: 1px solid black;
                    margin: auto;
                    height: 900px;
                }


                table, td, th {
                    border: 1px solid;
                    padding-left: 10px;
                    padding-right: 2px;
                    padding-top: 5px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                }

                /*#one, #two, #three {*/
                /*    float: left;*/
                /*    width: 33.33%;*/
                /*    height: 100px;*/
                /*}*/
                /*#one h1 {*/
                /*    font-size: 18px;*/
                /*    text-align: center;*/
                /*    font-weight: bold;*/
                /*}*/
                /*#three h1 {*/
                /*    font-size: 18px;*/
                /*    text-align: center;*/
                /*    font-weight: bold;*/
                /*}*/
                /*#one p {*/
                /*    font-weight: bold;*/
                /*}*/
                /*#three p {*/
                /*    font-weight: bold;*/
                /*}*/
                /*table, td, th {*/
                /*    border: 1px solid;*/
                /*    padding-left: 10px;*/
                /*    padding-top: 5px;*/
                /*}*/
                /*table {*/
                /*    width: 100%;*/
                /*    border-collapse: collapse;*/
                /*}*/
            }

            @media print {
                #hideOnScreen {
                    width: 800px;
                    background-color: white;
                    padding: 10px;
                    display: block;
                    /*border: 1px solid black;*/
                    margin: auto;
                    height: 950px;
                }



                #one, #two, #three {
                    float: left;
                    width: 33.33%;
                    height: 100px;
                }

                #one h1 {
                    font-size: 18px;
                    text-align: center;
                    font-weight: bold;
                }

                #three h1 {
                    font-size: 18px;
                    text-align: center;
                    font-weight: bold;
                }

                #one p {
                    font-weight: bold;
                }

                #three p {
                    font-weight: bold;
                }

                table {
                    page-break-inside: auto
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto
                }

                table, td, th {
                    border: 1px solid;
                    padding-left: 5px;
                    padding-right: 1px;
                    padding-top: 1px;
                    font-size: 10px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }
            }
        </style>
        <link rel="stylesheet" href="https://cms.ajkced.gok.pk/daterange/daterangepicker.min.css">
        <script src="https://cms.ajkced.gok.pk/daterange/jquery-3.6.0.min.js"></script>
        <script src="https://cms.ajkced.gok.pk/daterange/moment.min.js"></script>
        <script src="https://cms.ajkced.gok.pk/daterange/knockout-3.5.1.js" defer></script>
        <script src="https://cms.ajkced.gok.pk/daterange/daterangepicker.min.js" defer></script>

    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
            {{ __('Prisoner List') }}
        </h2>

        <div class="flex justify-center items-center float-right">
            <a href="{{route('prisoner.create')}}"
               class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
               title="Members List">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                <span class="hidden md:inline-block ml-2">Add Prisoner</span>
            </a>


            <a href="javascript:;" id="toggle"
               class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
               title="Members List">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span class="hidden md:inline-block ml-2">Search Filters</span>
            </a>


            @php
                $search_string = null;
                $cnic = null;
                $passport_no = null;
                $iqama_no = null;
                $status = null;
                $case_closing_reason = null;
                $case_closed = null;
                $search_date = null;
                $search_released = null;
                $search_expected = null;

                $detention_authority = null;
                $region = null;
                $detention_city = null;
                $prison = null;
                $search_from = null;
                $search_charges = null;

                empty(request()->get('filter')['search_string']) ? $search_string = null: $search_string = request()->get('filter')['search_string'];
                empty(request()->get('filter')['cnic']) ? $cnic = null: $cnic = request()->get('filter')['cnic'];
                empty(request()->get('filter')['passport_no']) ? $passport_no = null: $passport_no = request()->get('filter')['passport_no'];
                empty(request()->get('filter')['iqama_no']) ? $iqama_no = null: $iqama_no = request()->get('filter')['iqama_no'];
                empty(request()->get('filter')['status']) ? $status = null: $status = request()->get('filter')['status'];
                empty(request()->get('filter')['case_closing_reason']) ? $case_closing_reason = null: $case_closing_reason = request()->get('filter')['case_closing_reason'];
                empty(request()->get('filter')['case_closed']) ? $case_closed = null: $case_closed = request()->get('filter')['case_closed'];
                empty(request()->get('filter')['search_date']) ? $search_date = null: $search_date = request()->get('filter')['search_date'];
                empty(request()->get('filter')['search_released']) ? $search_released = null: $search_released = request()->get('filter')['search_released'];
                empty(request()->get('filter')['search_expected']) ? $search_expected = null: $search_expected = request()->get('filter')['search_expected'];

                empty(request()->get('filter')['detention_authority']) ? $detention_authority = null: $detention_authority = request()->get('filter')['detention_authority'];
                empty(request()->get('filter')['region']) ? $region = null: $region = request()->get('filter')['region'];
                empty(request()->get('filter')['detention_city']) ? $detention_city = null: $detention_city = request()->get('filter')['detention_city'];
                empty(request()->get('filter')['prison']) ? $prison = null: $prison = request()->get('filter')['prison'];
                empty(request()->get('filter')['search_from']) ? $search_from = null: $search_from = request()->get('filter')['search_from'];
                empty(request()->get('filter')['search_charges']) ? $search_charges = null: $search_charges = request()->get('filter')['search_charges'];


            @endphp

            <a href="{{route('prisoner.export',[
                'filter[search_string]' => $search_string,'filter[cnic]' => $cnic,'filter[passport_no]' => $passport_no,'filter[iqama_no]' => $iqama_no,'filter[status]' => $status,
                'filter[case_closing_reason]' => $case_closing_reason,'filter[case_closed]' => $case_closed,'filter[search_date]' => $search_date,
                'filter[search_released]' => $search_released,
                'filter[search_expected]' => $search_expected,

                'filter[detention_authority]' => $detention_authority,
                'filter[region]' => $region,
                'filter[detention_city]' => $detention_city,
                'filter[prison]' => $prison,
                'filter[search_from]' => $search_from,
                'filter[search_charges]' => $search_charges,
            ])}}"
               class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
               title="Download in Excel File">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>

            <button onclick="window.print()"
                    class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
                    title="Members List">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
            </button>


        </div>
    </x-slot>


    <div class="max-w-7xl mx-auto mt-12 px-4 sm:px-6 lg:px-8" style="display: none" id="filters">
        <div class="rounded-xl p-4 bg-white shadow-lg">
            <form action="">
                <div class="mb-3 -mx-2 flex items-end">
                    <div class="px-2 w-1/2">
                        <div>
                            <label class="font-bold text-sm mb-2 ml-1">Search</label>
                            <input name="filter[search_string]" value="" class="w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer"/>

                        </div>
                    </div>
                    <div class="px-2 w-1/2">
                        <label class="font-bold text-sm mb-2 ml-1">CNIC</label>
                        <input name="filter[cnic]" value=""
                               class="w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer"/>
                    </div>
                    <div class="px-2 w-1/2">
                        <label class="font-bold text-sm mb-2 ml-1">PASSPORT NO</label>
                        <input name="filter[passport_no]" value=""
                               class="w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer"/>
                    </div>
                    <div class="px-2 w-1/2">
                        <label class="font-bold text-sm mb-2 ml-1">IQAMA NO</label>
                        <input name="filter[iqama_no]" value=""
                               class=" w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer"/>
                    </div>


                </div>

                <div class="mb-3 -mx-2 flex items-end">
                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="detention_authority">
                            Detention authority
                        </label>
                        <select name="filter[detention_authority]" id="detention_authority" class="form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="" selected="">Please Select</option>
                            @foreach(\App\Models\Prisoner::detention_authority() as $item => $value)
                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="region">
                            Region
                        </label>
                        <select name="filter[region]" id="region" class="form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="" selected="">Please Select</option>
                            @foreach(\App\Models\Prisoner::regions() as $item => $value)
                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="detention_city">
                            Detention city
                        </label>
                        <select name="filter[detention_city]" id="detention_city" class="form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="" selected="">Please Select</option>
                            @foreach(\App\Models\Prisoner::detention_city() as $item => $value)
                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                            @endforeach
                        </select>
                    </div>




                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="prison">
                            Prison
                        </label>
                        <select name="filter[prison]" id="prison" class="form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="" selected="">Please Select</option>
                            @foreach(\App\Models\Prisoner::prisons() as $item => $value)
                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                            @endforeach
                        </select>
                    </div>


                </div>

                <div class="mb-3 -mx-2 flex items-end">
                    <div class="px-2 w-1/2">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="status">
                            Prisoner Status
                        </label>
                        <select name="filter[status]" id="status"
                                class=" form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="">
                                None
                            </option>
                            <option value="Detainee">
                                Detainee
                            </option>
                            <option value="Undertrial">
                                Undertrial
                            </option>
                            <option value="Sentenced">
                                Sentenced
                            </option>

                            <option value="Death Sentenced">
                                Death Sentenced
                            </option>

                            <option value="Released">
                                Released
                            </option>
                        </select>
                    </div>
                    <div class="px-2 w-1/2">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="case_closing_reason">
                            Case Closing Reason
                        </label>

                        <select name="filter[case_closing_reason]" id="case_closing_reason" class="form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="">Please Select</option>
                            <option value="None">None</option>
                            <option value="Deported">Deported</option>
                            <option value="Released">Released</option>
                            <option value="Executed">Executed</option>
                            <option value="Unknown">Unknown</option>
                        </select>


                    </div>
                    <div class="px-2 w-1/2">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="case_closed">
                            Case Closed
                        </label>

                        <select name="filter[case_closed]" id="case_closed" class="form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="">Please Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>


                    </div>

                    <div class="px-2 w-1/2">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="search_from">
                            Date Range (From - To)
                        </label>

                        <input type="search" readonly name="filter[search_from]" id="date_range" class="w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                    </div>
                </div>



                <div class="mb-3 -mx-2 flex items-end">
                    <div class="md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="search_charges">
                            charges crime
                        </label>
                        <select  style="width: 100%;" name="filter[search_charges]" id="search_charges" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                            <option value="">Please Select</option>
                            @foreach(\App\Models\Prisoner::crime_charges() as $item => $value)
                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="text-center">
                    <button type="submit" class=" px-4 py-2
                                bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white
                                uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700
                                focus:ring focus:ring-green-200 active:bg-green-600 disabled:opacity-25 transition ">
                        Search
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="py-12">

        <div style="margin: auto;font-size: 8px;" id="hideOnScreen">
            <div id="one">
                <h1>Embassy of Pakistan Riyadh,<br>Saudi Arabia
                <br>
                    Community Welfare Wing
                </h1>
            </div>
            <div id="two">
                <img src="{{Storage::url('logo.png')}}" style="height: 100px; display: block;margin-left: auto;margin-right: auto;" alt="">
            </div>
            <div id="three" style="direction: rtl;">

                <h1>
                    سفارت خانہ پاکستان ریاض،
                    <br>
                    سعودی عرب
                    <br>
                    شعبہ کمیونٹی ویلفیئر
                </h1>
            </div>

            @if($search_string || $cnic || $passport_no || $iqama_no || $status || $case_closing_reason || $case_closed || $search_date || $search_released || $search_expected || $detention_authority || $region || $detention_city || $prison || $search_from || $search_charges)


                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                   <h2 style="text-align: center; font-size: 12px;"> <span class="text-center"> Prisoner's Report<br>
                    Date: {{\Carbon\Carbon::now()->format('d-m-Y')}}
                   </h2>
                <p style="font-size: 12px;"> Search Criteria <br>
                    @if($search_string)
                        SEARCH: {{$search_string}} ,
                    @endif

                    @if($cnic)
                        CNIC: {{$cnic}}
                    @endif

                    @if($passport_no)
                        PASSPORT NO: {{$passport_no}} ,
                    @endif

                    @if($iqama_no)
                        IQAMA NO: {{$iqama_no}} ,
                    @endif

                    @if($detention_authority)
                        DETENTION AUTHORITY: {{$detention_authority}} ,
                    @endif

                    @if($region)
                        REGION: {{$region}} ,
                    @endif

                    @if($detention_city)
                        DETENTION CITY: {{$detention_city}} ,
                    @endif

                    @if($prison)
                        PRISON: {{$prison}} ,
                    @endif

                    @if($case_closing_reason)
                        CASE CLOSING REASON: {{$case_closing_reason}} ,
                    @endif

                    @if($case_closed)
                        CASE CLOSED: {{$case_closed}} ,
                    @endif


                    @if($search_charges)
                        CHARGES CRIME: {{$search_charges}} ,
                    @endif

                    @if($search_from)
                        DATE RANGE (FROM - TO): {{$search_from}} ,
                    @endif

                </p>


                <br>
                <br>



                <table>
                    <tr>
                        <td style="text-align: center">#</td>
                        <td style="text-align: center;">Name</td>
                        <td style="text-align: center;">Iqama</td>
                        <td style="text-align: center;">Passport</td>
                        <td style="text-align: center; width: 50px;">Charges</td>
                        <td style="text-align: center;">Detention Date</td>
                        <td style="text-align: center;">Status</td>
                        <td style="text-align: center;">Detention Period</td>
                        <td style="text-align: center;">Expected Release Date</td>
                        <td style="text-align: center;">Financial claim</td>
                        <td style="text-align: center;">Prison</td>
                    </tr>
                    @foreach($prisoner_print as $p)
                        <tr>
                            <td style="text-align: center">{{$loop->iteration}}</td>
                            <td>
                                    {{$p->name_and_father_name}}
                            </td>
                            <td style="text-align: center;">
                                {{$p->iqama_no}}
                            </td>
                            <td style="text-align: center;">
                                {{$p->passport_no}}
                            </td>
                            <td style="text-align: left;">
                                @if($p->prisoner_charges->isNotEmpty())
                                    @foreach($p->prisoner_charges as $charges)
                                        {{$charges->crime_charges}},
                                    @endforeach
                                @endif
                            </td>
                            <td style="text-align: center;" width="10%">
                                {{\Carbon\Carbon::parse($p->gregorian_detention_date)->format('d-m-Y')}} / {{$p->hijri_detention_date}}
                            </td>
                            <td style="text-align: center;">


                                    {{$p->case_closing_reason}}
                                        @if(!empty($p->case_closing_date_gg))
                                            on
                                            {{\Carbon\Carbon::parse($p->case_closing_date_gg)->format('d-m-Y')}}
                                        @endif


                                {{$p->status}} <br>
                                @if($p->sentence_in_years > 1)
                                    {{$p->sentence_in_years}} Years,
                                @else
                                    {{$p->sentence_in_years}} Year,
                                @endif


                                @if($p->sentence_in_months > 1)
                                    {{$p->sentence_in_months}} Months
                                @else
                                    {{$p->sentence_in_months}} Month
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if(!empty($p->gregorian_detention_date))
                                    {{\Carbon\Carbon::parse($p->gregorian_detention_date)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days')}}
                                @endif
                            </td>
                            <td style="text-align: center;"  width="10%">
                                @if(!empty($p->expected_release_date))
                                    {{\Carbon\Carbon::parse($p->expected_release_date)->format('d-m-Y')}}
                                @endif
                            </td>
                            <td style="text-align: center;">
                                {{$p->financial_claim}}
                            </td>
                            <td style="text-align: center;">
                                @if(!empty(\App\Models\Prison::where('jail', $p->prison)->first()))
                                    {{ \App\Models\Prison::where('jail', $p->prison)->first()->region }} -
                                    {{ $p->prison }}
                                @else
                                    {{$p->prison}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

        </div>

        <div class=" print:hidden max-w-12xl mx-auto sm:px-4 lg:px-8">
            {{ $prisoner->links() }}
            <div class="relative overflow-x-auto shadow-md ">
                <table
                    class="w-full text-xs border-collapse border border-slate-400 text-left text-black dark:text-gray-400 print:hidden">
                    <thead class="text-xs text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                    <tr class="bg-gray-200 text-gray-600 uppercase text-xs leading-normal">
                        <th class="py-3 px-6 text-left border border-black">Name</th>
                        <th class="py-3 px-6 text-center border border-black">Iqama</th>
                        <th class="py-3 px-6 text-center border border-black">Passport</th>
                        <th class="py-3 px-6 text-center border border-black">Charges/Case</th>
                        <th class="py-3 px-6 text-center border border-black">Detention Date</th>
                        <th class="py-3 px-6 text-center border border-black">Status</th>
                        <th class="py-3 px-6 text-center border border-black">Detention Period</th>
                        <th class="py-3 px-6 text-center border border-black">Expected Release Date</th>
                        <th class="py-3 px-6 text-center border border-black">Financial claim</th>
                        <th class="py-3 px-6 text-center border border-black">Prison</th>
                        {{--                        @canany(['Update Employee', 'Delete Employee'])--}}
                        <th class="py-3 px-6 text-center border border-black">Actions</th>
                        {{--                        @endcanany--}}
                    </tr>
                    </thead>
                    <tbody class="text-black text-xs font-light">
                    @foreach($prisoner as $p)
                        <tr class="border-b border-gray-200 bg-white text-black hover:bg-gray-100 print:hidden">
                            <td class="py-3 px-6 text-center border border-black ">
                                <a href="{{route('prisoner.show', $p->id)}}"
                                   class="flex items-center text-blue-500 hover:underline">
                                    <span>{{$p->name_and_father_name}}</span>
                                </a>
                            </td>
                            <td class="py-3 px-6 text-center border border-black ">
                                {{$p->iqama_no}}
                            </td>
                            <td class="py-3 px-6 text-center border border-black ">
                                {{$p->passport_no}}
                            </td>
                            <td class="py-3 px-6 text-left border border-black  break-words w-8">
                                @if($p->prisoner_charges->isNotEmpty())
                                    @foreach($p->prisoner_charges as $charges)
                                        {{$charges->crime_charges}},
                                    @endforeach
                                @endif
                            </td>

                            <td class="py-3 px-6 text-center border border-black ">
                                {{\Carbon\Carbon::parse($p->gregorian_detention_date)->format('d-m-Y')}}
                                / {{$p->hijri_detention_date}}
                            </td>


                            <td class="py-3 px-6 text-center border border-black ">

                                    <span class="text-red-500 font-bold">
                                    {{$p->case_closing_reason}}
                                        @if(!empty($p->case_closing_date_gg))
                                            on
                                            {{\Carbon\Carbon::parse($p->case_closing_date_gg)->format('d-m-Y')}}
                                        @endif

                                    </span>

                                {{$p->status}} <br>
                                @if($p->sentence_in_years > 1)
                                    {{$p->sentence_in_years}} Years,
                                @else
                                    {{$p->sentence_in_years}} Year,
                                @endif


                                @if($p->sentence_in_months > 1)
                                    {{$p->sentence_in_months}} Months
                                @else
                                    {{$p->sentence_in_months}} Month
                                @endif
                            </td>


                            <td class="py-3 px-6 text-center border border-black ">
                                @if(!empty($p->gregorian_detention_date))
                                    {{\Carbon\Carbon::parse($p->gregorian_detention_date)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days')}}
                                @endif
                            </td>


                            <td class="py-3 px-6 text-center border border-black ">
                                @if(!empty($p->expected_release_date))
                                    {{\Carbon\Carbon::parse($p->expected_release_date)->format('d-m-Y')}}
                                @endif
                            </td>

                            <td class="py-3 px-6 text-center border border-black ">
                                {{$p->financial_claim}}
                            </td>


                            <td class="py-3 px-6 text-center border border-black ">

                                @if(!empty(\App\Models\Prison::where('jail', $p->prison)->first()))
                                    {{ \App\Models\Prison::where('jail', $p->prison)->first()->region }} -
                                    {{ $p->prison }}
                                @else
                                    {{$p->prison}}
                                @endif
                            </td>

                            {{--                            @canany(['Update Employee', 'Delete Employee'])--}}
                            <td class="py-3 px-6 text-center border border-black ">
                                <div class="flex item-center justify-center">
                                    {{--                                        @can('Update Employee')--}}
                                    <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                        <a href="{{route('prisoner.edit', $p->id)}}" class="text-blue-500 underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>
                                    </div>
                                    {{--                                        @endcan--}}
                                    {{--                                        @can('Delete Employee')--}}
                                    <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                        <form action="{{route('prisoner.destroy', $p->id)}}" method="post"
                                              onSubmit="if(!confirm('Are you sure you want to delete?')){return false;}">
                                            @csrf

                                            @method('delete')
                                            <button type="submit" class="w-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    {{--                                        @endcan--}}
                                </div>
                            </td>
                            {{--                            @endcanany--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>


            </div>
            <br>
            {{ $prisoner->links() }}
        </div>
    </div>


    @section('custom_footer')
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js" defer></script>
        <script src="https://emis.ajk.gov.pk/assets/js/jquery.mask.js" defer></script>
        <script>
            $(document).ready(function () {
                $('.select2').select2();
                $('.cnic_mask').mask('00000-0000000-0');
                $('.date_mask').mask('0000-00-00 0000-00-00');
                $('.number_mask').mask('0000-0000000');
                $('.phone_mask').mask('00000-000000');
            });


            $(function () {
                $('#datepicker').keypress(function (event) {
                    event.preventDefault();
                    return false;
                });
            });


            const targetDiv = document.getElementById("filters");
            const btn = document.getElementById("toggle");
            btn.onclick = function () {
                if (targetDiv.style.display !== "none") {
                    targetDiv.style.display = "none";
                } else {
                    targetDiv.style.display = "block";
                }
            };


        </script>

        <script>
            $(document).ready(function () {
                $("#date_range").daterangepicker({
                    minDate: moment().subtract(10, 'years'),
                    orientation: 'left',
                    callback: function (startDate, endDate, period) {
                        $(this).val(startDate.format('L') + ' – ' + endDate.format('L'));
                    }
                });
            });
        </script>
    @endsection

</x-app-layout>
