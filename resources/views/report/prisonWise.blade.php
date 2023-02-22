<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline-block">
           Report Prison Wise
        </h2>


    </x-slot>




    <div class="py-12">

        <div class="max-w-18xl mx-auto sm:px-4 lg:px-8">
            <div class="relative overflow-x-auto shadow-md ">
                <table class="w-full text-sm border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
                    <thead class="text-black uppercase bg-gray-50 dark:bg-gray-700 ">
                    <tr>
                        <th scope="col" class="px-1 py-3 border border-black text-center " colspan="3" >
                            Date: {{\Carbon\Carbon::now()->format('d-m-Y')}}
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
