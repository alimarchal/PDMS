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
                @page {size: landscape}
                #hideOnScreen_1 {
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
                    padding-left: 10px;
                    padding-right: 2px;
                    padding-top: 5px;
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
                <h1>Embassy of Pakistan Riyadh,<br>Saudi Arabia
                    <br>
                    Community Welfare Wing
                </h1>
            </div>
            <div id="two" style="margin-bottom: 10px;">
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



            <table>
                <thead>
                <tr>
                    <th scope="col" colspan="24">
                        Statistics of Pakistani prisoners in Saudi jails under consuler jurisdiction of Emassy of
                        Pakistan Riyadh
                    </th>
                </tr>
                <tr>
                    <th scope="col" colspan="24" >
                        Crime Wise
                    </th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <th>
                        Region
                    </th>
                    <th>
                        Jail
                    </th>
                    @foreach(\App\Models\Prisoner::crime_charges() as $key => $value)

                        <td style="font-size: 10px;transform: rotate(-90deg); text-align: center">
                            {{$key}}
                        </td>

                    @endforeach
                    <td>
                        Total
                    </td>
                    <td>
                        Undertrial
                    </td>
                    <td style="transform: rotate(-90deg);">
                        Sentenced
                    </td>
                </tr>

                @foreach($region_wise as $key => $value)

                    @php $flag = true; @endphp
                    @foreach($value as $k => $v)
                        <tr>
                            @if($flag)
                                <td rowspan="{{count($value)}}">{{$key}} </td>
                            @endif
                            @php $flag = false; @endphp
                            <td >{{$k}}</td>
                            @php $total = 0; @endphp
                            @foreach(\App\Models\Prisoner::crime_charges() as $x => $y)
                                <td style="text-align: center;">{{$v[$x]}}</td>
                                @php $total = $total + $v[$x]; @endphp
                            @endforeach
                            <td style="text-align: center;">{{$total}}</td>
                            <td style="text-align: center;">
                                {{\App\Models\Prisoner::where('status','Undertrial')->where('prison',$k)->count()}}
                            </td>
                            <td style="text-align: center;">{{\App\Models\Prisoner::where('status','Sentenced')->where('prison',$k)->count()}}</td>
                        </tr>

                    @endforeach

                @endforeach


                <tr>
                    <td  colspan="2">Total</td>
                    @foreach($grand_total as $a => $b)
                        <td>
                            {{$b}}
                        </td>
                    @endforeach
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>


                </tbody>
            </table>

        </div>


        <div class="py-12  print:hidden">
        <div class=" print:hidden max-w-18xl mx-auto sm:px-4 lg:px-8" >
            <div class="relative overflow-x-auto shadow-md ">
                <table class="w-full text-sm border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
                    <thead class="text-xs text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                    <tr>
                        <th scope="col" colspan="24" class="px-1 py-3 border border-black text-center">
                            Statistics of Pakistani prisoners in Saudi jails under consuler jurisdiction of Emassy of
                            Pakistan Riyadh
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" colspan="24" class="px-1 py-3 border border-black text-center">
                            Crime Wise
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-black text-center">
                        <th class="px-16 border border-black  py-2 font-medium text-black dark:text-white">
                            Region
                        </th>
                        <th
                            class="px-24  border border-black py-0 font-medium text-black dark:text-white">
                            Jail
                        </th>
                        @foreach(\App\Models\Prisoner::crime_charges() as $key => $value)

                                <td class=" py-3 px-2 border border-black font-bold " style="font-size: 12px; line-height: normal">
                                    {{$key}}
                                </td>

                        @endforeach
                        <td class="px-0  border border-black   -rotate-90 ">
                            Total
                        </td>
                        <td class="px-0  border border-black   -rotate-90 ">
                            Undertrial
                        </td>
                        <td class="px-0  border border-black   -rotate-90 ">
                            Sentenced
                        </td>
                    </tr>

                    @foreach($region_wise as $key => $value)

                        @php $flag = true; @endphp
                        @foreach($value as $k => $v)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-black">
                                @if($flag)
                                    <td class="px-5 border border-black text-left" style="font-family: Arial" rowspan="{{count($value)}}">{{$key}} </td>
                                @endif
                                @php $flag = false; @endphp
                                <td class="px-2 py-2 border border-black text-left">{{$k}}</td>
                                @php $total = 0; @endphp
                                @foreach(\App\Models\Prisoner::crime_charges() as $x => $y)
                                    <td class="px-2 py-2 border border-black text-right">{{$v[$x]}}</td>
                                    @php $total = $total + $v[$x]; @endphp
                                @endforeach
                                <td class="px-1 py-2 border border-black text-right">{{$total}}</td>
                                <td class="px-2 py-2 border border-black text-right">
                                    {{\App\Models\Prisoner::where('status','Undertrial')->where('prison',$k)->count()}}
                                </td>
                                <td class="px-1 py-2 border border-black text-right">{{\App\Models\Prisoner::where('status','Sentenced')->where('prison',$k)->count()}}</td>
                            </tr>

                        @endforeach

                    @endforeach


                    <tr class="text-center font-bold">
                        <td class="px-2 py-2 border border-black" colspan="2">Total</td>
                        @foreach($grand_total as $a => $b)
                            <td class="px-2 py-2 border border-black text-right">
                                {{$b}}
                            </td>
                        @endforeach
                        <td class="px-2 py-2 border border-black text-right">0</td>
                        <td class="px-2 py-2 border border-black text-right">0</td>
                        <td class="px-2 py-2 border border-black text-right">0</td>
                    </tr>


                    </tbody>
                </table>
            </div>
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
