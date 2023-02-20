<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prisoner Shifting Data
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-10 bg-white border-b border-gray-200">
                    <div class="mt-6 text-gray-500">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">{{$error}}</strong>
                                </div>
                                <br>
                            @endforeach
                        @endif

                        <form action="{{route('prisionerShifted.store')}}" class="mb-6" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="prisoner_id" value="{{$prisoner->id}}">
                            <div class="bg-white rounded px-8 pt-6 pb-8 ">
                                <div class="-mx-3 md:flex mb-1">
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="shifting_date_hijri">
                                            Shifting Date (Hijri Date)
                                        </label>
                                        <input name="shifting_date_hijri" placeholder="dd-mm-YYYY" min="10" required class="hdate appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                               id="shifting_date_hijri" type="text">
                                    </div>


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="detention_authority">
                                            Detention authority
                                        </label>
                                        <select name="detention_authority" id="detention_authority" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::detention_authority() as $item => $value)
                                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-detention_place">
                                            Detention Place
                                        </label>
                                        <input name="detention_place" class=" appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                               id="grid-detention_place" type="text">
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="detention_city">
                                            Detention city
                                        </label>
                                        <select name="detention_city" id="detention_city" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::detention_city() as $item => $value)
                                                <option value="{{$item}}">{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>

                                <div class="-mx-3 md:flex mb-1">

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="shifting_date_hijri">
                                            Other Details
                                        </label>
                                        <textarea name="other_details" id="other_details" class="w-full uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" ></textarea>
                                    </div>
                                </div>


                                <div style="float: right" class="mt--1">
                                    <button class="bg-blue-500 hover:bg-blue-400
                                    text-white font-bold py-2 px-4 border-b-4
                                    border-blue-700 hover:border-blue-500 rounded">
                                        <span>Submit</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
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
                $('.hdate').mask('00-00-0000');
            });


            $(function () {
                $('#datepicker').keypress(function (event) {
                    event.preventDefault();
                    return false;
                });
            });
        </script>
    @endsection

</x-app-layout>
