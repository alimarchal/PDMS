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
           Report Prison Wise
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




    <div class="py-12">

        <div class="max-w-18xl mx-auto sm:px-4 lg:px-8">
            <div class="relative overflow-x-auto shadow-md ">

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
                </div>



                <table class="w-full text-sm border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
                    <thead class="text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                    <tr>
                        <th scope="col" class="px-1 py-3 border border-black text-center " colspan="3" >
                            Date: {{\Carbon\Carbon::now()->format('d-m-Y')}} - Report Prison Wise
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-1 py-3 border border-black " width="33.34%">
                            Prison
                        </th>
                        <th scope="col" class="px-1 py-3 border border-black  text-center"  width="33.33%">
                            Number of prisoners
                        </th>
                        <th scope="col" class="px-1 py-3 border border-black  text-center"  width="5%">
                            %
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    @php
                        $total_percentage = 0;
                    @endphp
                    @foreach($prison_wise as $key => $value)
                        <tr class="bg-white  border-b dark:bg-gray-800 dark:border-black text-left">
                            <th class="border px-2 py-2  border-black font-medium text-black dark:text-white">
                                {{$key}} - ({{\App\Models\Prison::where('jail', $key)->first()->region}})
                            </th>
                            <th class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white text-center">
                                {{$value}}
                            </th>
                            <td class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white text-center">
                                {{number_format(($value / $total *100),2)}}%

                                @php
                                    $total_percentage = $total_percentage + (round($value / $total *100,2));
                                @endphp
                            </td>
                        </tr>
                    @endforeach

                    <tr class="bg-white  border-b dark:bg-gray-800 dark:border-black text-left">
                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold">
                            Total
                        </th>

                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold" colspan="2">
                            {{number_format($total,0)}}
                        </th>


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
