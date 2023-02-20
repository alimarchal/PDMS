<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
            Report Region Wise
        </h2>


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

    <div class="py-12">

        <div class="max-w-18xl mx-auto sm:px-4 lg:px-8">
            <div class="relative overflow-x-auto shadow-md ">
                <table class="w-full text-sm border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
                    <thead class="text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                    <tr>
                        <th scope="col" class="px-1 py-3 border border-black text-center " colspan="6">
                            Date: {{\Carbon\Carbon::now()->format('d-m-Y')}}
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-1 py-3 border border-black text-center " width="2%">
                            S.No
                        </th>
                        <th scope="col" class="px-1 py-3 border border-black " width="5%">
                            Region
                        </th>
                        <th scope="col" class="px-1 py-3 border border-black  text-center" width="10%">
                            Sentenced
                        </th>
                        <th scope="col" class="px-1 py-3 border border-black  text-center" width="10%">
                            Undertrail
                        </th>

                        <th scope="col" class="px-1 py-3 border border-black  text-center" width="10%">
                            Death Sentenced
                        </th>
                        <th scope="col" class="px-1 py-3 border border-black  text-center" width="5%">
                            Total
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    @php
                        $total_percentage = 0;
                        $i = 1;
                        $sentenced = 0;
                        $undertrial = 0;
                        $Death_Sentenced = 0;
                    @endphp
                    @foreach($region_wise as $key => $value)
                        <tr class="bg-white  border-b dark:bg-gray-800 dark:border-black text-left">
                            <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center">
                               {{$i}}
                                @php
                                    $i++;
                                @endphp
                            </th>
                            <th class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white">
                                {{$key}}
                            </th>
                            <td class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white text-center">

                                    {{$value['Sentenced']}}
                                    @php $sentenced = $sentenced + $value['Sentenced']; @endphp
                            </td>

                            <td class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white text-center">
                                    {{$value['Undertrial']}}
                                    @php $undertrial = $undertrial + $value['Undertrial']; @endphp
                            </td>

                            <td class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white text-center">
                                {{$value['Death Sentenced']}}
                                @php $Death_Sentenced = $Death_Sentenced + $value['Death Sentenced']; @endphp
                            </td>

                            <td class="border px-2 py-2 border-black py-0 font-medium text-black dark:text-white text-center">
                                {{$value['Sentenced'] + $value['Undertrial']}}
                            </td>
                        </tr>
                    @endforeach

                    <tr class="bg-white  border-b dark:bg-gray-800 dark:border-black text-left">
                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold" colspan="2">
                            Total
                        </th>
                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold" >
                            {{$sentenced}}
                        </th>
                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold" >
                            {{$undertrial}}
                        </th>

                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold" >
                            {{$Death_Sentenced}}
                        </th>
                        <th class="border px-2 py-2  border-black font-medium text-black dark:text-white text-center font-bold" >
                            {{number_format($sentenced+$undertrial+$Death_Sentenced,0)}}
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
