<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
        <style>
            @media screen {
                #hideOnScreen {
                    display: none;
                }
            }

            @media print {
                /*@page {size: landscape}*/
                #hideOnScreen_1 {
                    width: auto;
                    background-color: white;
                    /*padding: 10px;*/
                    display: block;
                    /*border: 1px solid black;*/
                    margin: auto;
                    height: auto;
                }

                #one, #two, #three {
                    float: left;
                    width: 33.33%;
                    height: 100px;
                }

                #one h1 {
                    font-size: 16px;
                    text-align: center;
                    font-weight: bold;
                }

                #three h1 {
                    font-size: 16px;
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
                    /*page-break-inside: auto*/
                }

                tr {
                    /*page-break-inside: avoid;*/
                    /*page-break-after: auto*/
                }

                table, td, th {
                    border: 1px solid;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }
            }
        </style>
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
            {{ __('Prisoner List') }}
        </h2>

        <div class="flex justify-center items-center float-right">


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
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                               for="prison">
                            Prison
                        </label>
                        <select name="filter[prison]" id="prison"
                                class=" form-select w-full px-3 py-2 mb-1 border-2
                                border-gray-200 rounded-md focus:outline-none
                                focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="" selected="">Please Select</option>
                            @foreach(\App\Models\Prisoner::prisons() as $item => $value)
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


    <div id="hideOnScreen" style="margin: auto;font-size: 11px;">

        <div id="one">
            <span style="font-weight: bold;">Print Date: {{date('d-M-Y')}}</span>
            <h1>Embassy of Pakistan Riyadh,<br>Saudi Arabia
                <br>
                Community Welfare Wing
            </h1>
        </div>
        <div id="two" style="margin-bottom: 10px;">
            <img src="{{Storage::url('logo.png')}}" style="height: 100px; display: block;margin-left: auto;margin-right: auto;" alt="">
        </div>
        <div id="three" style="direction: rtl;">
            <br>
            <h1>
                سفارت خانہ پاکستان ریاض،
                <br>
                سعودی عرب
                <br>
                شعبہ کمیونٹی ویلفیئر
            </h1>
        </div>

        <table>
            <thead>
            <tr>

                <th scope="col" colspan="2">
                    Grand Total: {{$total_grand_value}}
                </th>

                <th scope="col" colspan="25">
                    Statistics of Pakistani prisoners in Saudi jails under consuler jurisdiction of Emassy of
                    Pakistan Riyadh
                </th>
            </tr>
            <tr>
                <th scope="col" colspan="27">
                    Crime Wise
                </th>
            </tr>

            </thead>
            <tbody>

            <tr>
                <th style="width: 150px">
                    Region
                </th>
                <th style="width: 150px">
                    Jail
                </th>


                @foreach(\App\Models\CrimeCharges::all() as $item)

                    <td style="font-size: 12px; height:150px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap; padding-left: 5px;">
                        {{$item->name}}
                    </td>

                @endforeach
                <td style="font-size: 12px; height:150px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap; padding-left: 5px;">
                    Total
                </td>
                <td style="font-size: 12px; height:150px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap; padding-left: 5px;">
                    Undertrial
                </td>
                <td style="font-size: 12px; height:150px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap; padding-left: 5px;">
                    Sentenced
                </td>
            </tr>

            @foreach($region_wise as $key => $value)
                @php $flag = true; @endphp
                @foreach($value as $k => $v)
                    <tr style="text-align: center">
                        @if($flag)
                            <td style="font-family: Arial; text-align: left;" rowspan="{{count($value)}}">{{$key}}  </td>
                        @endif
                        @php $flag = false; @endphp
                        <td style="text-align: left;">{{$k}}</td>
                        @php
                            $total_row = 0;
                        @endphp
                        @foreach(\App\Models\CrimeCharges::all() as $item)
                            <td>
                                @if($v[$item->name] == 0)
                                    -
                                @else
                                    {{$v[$item->name]}}
                                @endif
                            </td>
                            @php
                                $total_row = $total_row + $v[$item->name];
                            @endphp
                        @endforeach
                        <td style="text-align: center!important;">
                            @if($total_row == 0)
                                -
                            @else
                                {{$total_row}}
                            @endif
                        </td>
                        <td>
                            @php
                                $ut = \App\Models\Prisoner::whereIn('prisoners.status',['Undertrial'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')
                                ->where('region',$key)->where('prison',$k)->count();
                            @endphp

                            @if($ut == 0)
                                -
                            @else
                                {{$ut}}
                            @endif
                        </td>
                        <td>
                            @php
                                $st = \App\Models\Prisoner::whereIn('prisoners.status',['Sentenced', 'Death Sentenced'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')
                                    ->where('region',$key)->where('prison',$k)->count()
                            @endphp
                            @if($st == 0)
                                -
                            @else
                                {{$st}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach

            <tr>
                <td colspan="2" style="text-align:center;background-color: yellow;">Total</td>
                @foreach($grand_total as $key)
                    <td style="text-align:center;">{{$key}}</td>
                @endforeach
                <td style="text-align:center;">{{$total_grand_value}}</td>
                <td style="text-align:center;">
                    {{ \App\Models\Prisoner::whereIn('prisoners.status',['Undertrial'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')->count()}}
                </td>
                <td style="text-align:center;">
                    {{ \App\Models\Prisoner::whereIn('prisoners.status',['Sentenced', 'Death Sentenced'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')->count()}}
                </td>
            </tr>
            </tbody>
        </table>

    </div>


    <div class="py-12 print:hidden">
        <div class=" print:hidden max-w-18xl mx-auto sm:px-4 lg:px-8">
            <table class="w-full text-sm border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
                <thead class="text-xs text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                <tr>
                    <th scope="col" colspan="27" class="px-1 py-3 border border-black text-center">
                        Statistics of Pakistani prisoners in Saudi jails under consuler jurisdiction of Emassy of
                        Pakistan Riyadh
                    </th>
                </tr>
                <tr>
                    <th scope="col" colspan="2" class="px-1 py-3 border border-black text-center" style="background-color: yellow;">
                        Grand Total: {{$total_grand_value}}
                    </th>
                    <th scope="col" colspan="25" class="px-1 py-3 border border-black text-center">
                        Crime Wise
                    </th>
                </tr>
                </thead>
                <tbody>

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-black text-center">
                    <th class="border border-black text-black dark:text-white" style="width: 150px">
                        Region
                    </th>
                    <th
                        class="border border-black font-bold dark:text-white" style="width: 150px">
                        Jail
                    </th>
                    @foreach(\App\Models\CrimeCharges::all() as $item)

                        <td class="border border-black font-bold " style="font-size: 12px; height:200px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap;">
                            {{$item->name}}
                        </td>

                    @endforeach
                    <td class="px-0  border border-black font-bold"
                        style="background-color: yellow;font-size: 12px; height:200px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap;">
                        Total
                    </td>
                    <td class="px-0  border border-black font-bold" style="font-size: 12px; height:200px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap;">
                        Undertrial
                    </td>
                    <td class="px-0  border border-black font-bold" style="font-size: 12px; height:200px; translate(25px, 51px) /* 45 is really 360 - 45 */ rotate(315deg); width: 30px; writing-mode: vertical-lr;white-space: nowrap;">
                        Sentenced
                    </td>
                </tr>


                @foreach($region_wise as $key => $value)
                    @php $flag = true; @endphp
                    @foreach($value as $k => $v)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-black">
                            @if($flag)
                                <td class="px-5 border border-black text-left" style="font-family: Arial" rowspan="{{count($value)}}">{{$key}}  </td>
                            @endif
                            @php $flag = false; @endphp
                            <td class="px-2 py-2 border border-black text-left">{{$k}}</td>
                            @php
                                $total_row = 0;
                            @endphp
                            @foreach(\App\Models\CrimeCharges::all() as $item)
                                <td class="px-2 py-2 border border-black text-center">
                                    @if($v[$item->name] == 0)
                                        -
                                    @else
                                        {{$v[$item->name]}}
                                    @endif

                                </td>
                                @php
                                    $total_row = $total_row + $v[$item->name];
                                @endphp
                            @endforeach
                            <td class="px-2 py-2 border border-black text-left" style="text-align:center;background-color: yellow;">
                                @if($total_row == 0)
                                    -
                                @else
                                    {{$total_row}}
                                @endif
                            </td>
                            <td class="px-2 py-2 border border-black text-left">
                                @php
                                    $ut = \App\Models\Prisoner::whereIn('prisoners.status',['Undertrial'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')
                                    ->where('region',$key)->where('prison',$k)->count();
                                @endphp

                                @if($ut == 0)
                                    -
                                @else
                                    {{$ut}}
                                @endif
                            </td>
                            <td class="px-2 py-2 border border-black text-left">
                                @php
                                    $st = \App\Models\Prisoner::whereIn('prisoners.status',['Sentenced', 'Death Sentenced'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')
                                        ->where('region',$key)->where('prison',$k)->count()
                                @endphp
                                @if($st == 0)
                                    -
                                @else
                                    {{$st}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-black">
                    <td class="px-2 py-2 border border-black text-left" colspan="2" style="text-align:center;background-color: yellow;">Total</td>
                    @foreach($grand_total as $key)
                        <td class="px-2 py-2 border border-black text-left" style="text-align:center;background-color: yellow;">{{$key}}</td>
                    @endforeach
                    <td class="px-2 py-2 border border-black text-left" style="text-align:center;background-color: yellow;">{{$total_grand_value}}</td>
                    <td class="px-2 py-2 border border-black text-left" style="text-align:center;background-color: yellow;">
                        {{ \App\Models\Prisoner::whereIn('prisoners.status',['Undertrial'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')->count()}}
                    </td>
                    <td class="px-2 py-2 border border-black text-left" style="text-align:center;background-color: yellow;">
                        {{ \App\Models\Prisoner::whereIn('prisoners.status',['Sentenced', 'Death Sentenced'])->whereNotNull('prisoners.prison')->where('prisoners.case_closed','=','No')->count()}}
                    </td>
                </tr>


                </tbody>
            </table>
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
    @endsection

</x-app-layout>
