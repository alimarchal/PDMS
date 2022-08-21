<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
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
                empty(request()->get('filter')['search_string']) ? $search_string = null: $search_string = request()->get('filter')['search_string'];
                empty(request()->get('filter')['cnic']) ? $cnic = null: $cnic = request()->get('filter')['cnic'];
                empty(request()->get('filter')['passport_no']) ? $passport_no = null: $passport_no = request()->get('filter')['passport_no'];
                empty(request()->get('filter')['iqama_no']) ? $iqama_no = null: $iqama_no = request()->get('filter')['iqama_no'];
                empty(request()->get('filter')['status']) ? $status = null: $status = request()->get('filter')['status'];
            @endphp

            <a href="{{route('prisoner.export',['filter[search_string]' => $search_string,'filter[cnic]' => $cnic,'filter[passport_no]' => $passport_no,'filter[iqama_no]' => $iqama_no,'filter[status]' => $status])}}"
               class="flex items-center px-4 py-2 text-gray-600 bg-white border rounded-lg focus:outline-none hover:bg-gray-100 transition-colors duration-200 transform dark:text-gray-200 dark:border-gray-200  dark:hover:bg-gray-700 ml-2"
               title="Download in Excel File">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>


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

        <div class="max-w-12xl mx-auto sm:px-4 lg:px-8">

            <div class="relative overflow-x-auto shadow-md ">
                <table
                    class="w-full text-xs border-collapse border border-slate-400 text-left text-black dark:text-gray-400">
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
                        <tr class="border-b border-gray-200 bg-white text-black hover:bg-gray-100">
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
                                {{$p->prison}}
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
