<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Prisoner') }}
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

                        <form action="{{route('prisoner.update', $prisoner->id)}}" class="mb-6" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="bg-white rounded px-8 pt-6 pb-8 ">

                                <div class="-mx-3 md:flex mb-3">
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-name_and_father_name">
                                            Name & Father name
                                        </label>
                                        <input name="name_and_father_name" value="{{$prisoner->name_and_father_name}}" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-name_and_father_name" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-arabic_name">
                                            Arabic Name
                                        </label>
                                        <input name="arabic_name" value="{{$prisoner->arabic_name}}" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-arabic_name" type="text">
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-iqama_no">
                                            iqama no
                                        </label>
                                        <input name="iqama_no" value="{{$prisoner->iqama_no}}" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-iqama_no" type="text">
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-passport_no">
                                            passport no
                                        </label>
                                        <input name="passport_no" value="{{$prisoner->passport_no}}" placeholder="AB123456789" class=" appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                               id="grid-passport_no" type="text">
                                    </div>

                                </div>


                                <div class="-mx-3 md:flex mb-1">
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="detention_authority">
                                            Detention authority
                                        </label>
                                        <select name="detention_authority" id="detention_authority" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::detention_authority() as $item => $value)
                                                <option value="{{$item}}" @if($prisoner->detention_authority == $item) selected @endif >{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-detention_place">
                                            Detention Place
                                        </label>
                                        <input name="detention_place"  value="{{$prisoner->detention_place}}" class=" appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                               id="grid-detention_place" type="text">
                                    </div>



                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="region">
                                            Region
                                        </label>
                                        <select name="region" id="region" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::regions() as $item => $value)
                                                <option value="{{$item}}"  @if($prisoner->region == $item) selected @endif  >{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="detention_city">
                                            Detention city
                                        </label>
                                        <select name="detention_city" id="detention_city" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::detention_city() as $item => $value)
                                                <option value="{{$item}}"  @if($prisoner->detention_city == $item) selected @endif  >{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="prison">
                                            Prison
                                        </label>
                                        <select name="prison" id="prison" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::prisons() as $item => $value)
                                                <option value="{{$item}}"  @if($prisoner->prison == $item) selected @endif  >{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>

                                <br>



                                <div class="-mx-3 md:flex mb-3">




                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-hijri_detention_date">
                                            hijri detention date
                                        </label>
                                        <input name="hijri_detention_date" class="hdate appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                               id="grid-hijri_detention_date" type="text" min="10" placeholder="dd-mm-yyyy" value="{{$prisoner->hijri_detention_date}}" wire:change="hijri_detention_date($event.target.value)"  >

                                        {{--        value="{{$hijri_date}}" onkeydown="return false"  --}}
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-gregorian_detention_date">
                                            gregorian detention date
                                        </label>
                                        <input name="gregorian_detention_date"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                               id="grid-gregorian_detention_date" type="date" disabled  value="{{$prisoner->gregorian_detention_date}}" onkeydown="return false" >
                                    </div>


                                </div>

                                <div class="-mx-3 md:flex mb-1">
                                    <div class="w-full px-3" style="width: 100%;">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="crime_charges">
                                            charges crime
                                        </label>
                                        <select name="crime_charges[]" multiple id="crime_charges" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" required="">
                                            @foreach(\App\Models\Prisoner::crime_charges() as $item => $value)
                                                <option value="{{$item}}"
                                                    @if($prisoner->prisoner_charges->isNotEmpty())
                                                    @foreach($prisoner->prisoner_charges as $ch)
                                                        @if($ch->crime_charges == $item)
                                                            selected
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                >{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>

                                <div class="-mx-3 md:flex mb-1">
                                    <div class="w-full px-3" row="8">
                                        <label class="block uppercase resize rounded-md tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-case_details">
                                            case details
                                        </label>
                                        <textarea name="case_details" rows="5" class="appearance-none form-textarea w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-case_details">{{$prisoner->case_details}}</textarea>
                                    </div>
                                </div>





                                <hr>
                                <h1 class="text-2xl text-red-500 text-center m-4">Sentence </h1>
                                <hr>

                                <br>


                                <div class="-mx-3 md:flex mb-1">

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="sentence_in_years">
                                            Years
                                        </label>
                                        <select name="sentence_in_years" id="sentence_in_years" class="  appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" required="">
                                            <option value="" selected="">Please Select</option>
                                            @for($i = 0; $i<= 50; $i++)
                                                <option value="{{$i}}" @if($prisoner->sentence_in_years == $i) selected @endif >
                                                    @if($i == 1 || $i == 0)
                                                        {{$i}} Year
                                                    @else
                                                        {{$i}} Years
                                                    @endif
                                                </option>
                                            @endfor

                                        </select>
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="sentence_in_months">
                                            Months
                                        </label>
                                        <select name="sentence_in_months" id="sentence_in_months" class=" appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" required="">
                                            <option value="" selected="">Please Select</option>
                                            @for($i = 0; $i<= 11; $i++)
                                                <option value="{{$i}}" @if($prisoner->sentence_in_months == $i) selected @endif >
                                                    @if($i == 1 || $i == 0)
                                                        {{$i}} Month
                                                    @else
                                                        {{$i}} Months
                                                    @endif
                                                </option>
                                            @endfor

                                        </select>
                                    </div>


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-financial_claim">
                                            Financial claim
                                        </label>
                                        <input name="financial_claim" value="{{$prisoner->financial_claim}}" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-financial_claim" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-penalty_fine">
                                            Penalty / Fine
                                        </label>
                                        <input name="penalty_fine" value="{{$prisoner->penalty_fine}}" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-penalty_fine" type="text">
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="status">
                                            Prisoner Status
                                        </label>

                                        <select name="status" id="status" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">

                                            @foreach(\App\Models\Prisoner::prisoner_status() as $item)
                                                <option value="{{$item}}"  @if($item == $prisoner->status) selected @endif>
                                                    {{$item}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <hr>
                                <h1 class="text-2xl text-red-500 text-center m-4">Case Details</h1>
                                <hr>

                                <br>


                                <div class="-mx-3 md:flex mb-3">
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_court_name">
                                            Court
                                        </label>
                                        <select name="case_court_name" id="case_court_name" class=" appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::courts() as $key => $value)
                                                <option value="{{$key}}" @if($prisoner->case_court_name == $key) selected @endif >{{$key}}-{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_city">
                                            City
                                        </label>
                                        <select name="case_city" id="case_city" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::detention_city() as $item => $value)
                                                <option value="{{$item}}" @if($prisoner->case_city == $item) selected @endif >{{$item}} - {{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_number">
                                            Case number
                                        </label>
                                        <input name="case_number" value="{{$prisoner->case_number}}" id="case_number" type="text" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" >
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_prisoner_number">
                                            Prisoner number
                                        </label>
                                        <input name="case_prisoner_number"  value="{{$prisoner->case_prisoner_number}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_prisoner_number" type="text" >
                                    </div>
                                </div>


                                <div class="-mx-3 md:flex mb-3">
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_claim_number">
                                            Claim number
                                        </label>
                                        <input name="case_claim_number"   value="{{$prisoner->case_claim_number}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_claim_number" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_sadad_number">
                                            Sadad number
                                        </label>
                                        <input name="case_sadad_number"   value="{{$prisoner->case_sadad_number}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_sadad_number" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_claimer_name">
                                            Claimer name
                                        </label>
                                        <input name="case_claimer_name" value="{{$prisoner->case_claimer_name}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_claimer_name" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_claimer_contact_number">
                                            Claimer contact number
                                        </label>
                                        <input name="case_claimer_contact_number" value="{{$prisoner->case_claimer_contact_number}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_claimer_contact_number" type="text">
                                    </div>
                                </div>
                                <hr>
                                <h1 class="text-2xl text-red-500 text-center m-4">Legal Assistance and Case Closing </h1>
                                <hr>
                                <br>

                                <div class="-mx-3 md:flex mb-3">
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_consular_access_date">
                                            Consular access date
                                        </label>
                                        <input name="case_consular_access_date" value="{{$prisoner->case_consular_access_date}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_consular_access_date" type="date">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="etd_issuance_date">
                                            ETD issuance date
                                        </label>
                                        <input name="etd_issuance_date" value="{{$prisoner->etd_issuance_date}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="etd_issuance_date" type="date">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="etd_number">
                                            ETD number
                                        </label>
                                        <input name="etd_number" value="{{$prisoner->etd_number}}" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="etd_number" type="text">
                                    </div>

                                </div>


                                <div class="-mx-3 md:flex mb-3">


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_closing_reason">
                                            Case Closing Reason
                                        </label>
                                        <select name="case_closing_reason" id="case_closing_reason" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="">Please Select</option>
                                            <option value="None" @if($prisoner->case_closing_reason == "None") selected @endif >None</option>
                                            <option value="Deported" @if($prisoner->case_closing_reason == "Deported") selected @endif >Deported</option>
                                            <option value="Released" @if($prisoner->case_closing_reason == "Released") selected @endif >Released</option>
                                            <option value="Executed" @if($prisoner->case_closing_reason == "Executed") selected @endif >Executed</option>
                                            <option value="Unknown" @if($prisoner->case_closing_reason == "Unknown") selected @endif >Unknown</option>
                                        </select>
                                    </div>




                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_closed">
                                            Case closed
                                        </label>
                                        <select name="case_closed" id="case_closed" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="">Please Select</option>
                                            <option value="Yes" @if($prisoner->case_closed == "Yes") selected @endif >Yes</option>
                                            <option value="No"  @if($prisoner->case_closed == "No") selected @endif >No</option>
                                        </select>
                                    </div>



                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="case_closing_date_hijri">
                                            Closing date (Hijri Date)
                                        </label>
                                        <input name="case_closing_date_hijri" value="{{$prisoner->case_closing_date_hijri}}" placeholder="dd-mm-YYYY" class="hdate appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="case_closing_date_hijri" type="text" >
                                    </div>
                                </div>


                                <br>
                                <hr>
                                <h1 class="text-2xl text-red-500 text-center m-4">Personal Details</h1>
                                <hr>
                                <br>

                                <div class="-mx-3 md:flex mb-3">



                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="gender">
                                            gender
                                        </label>
                                        <select name="gender" id="gender" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            <option value="Male"   @if($prisoner->gender == "Male") selected @endif  >
                                                Male
                                            </option>
                                            <option value="Female"  @if($prisoner->gender == "Female") selected @endif>
                                                Female
                                            </option>
                                        </select>
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-cnic">
                                            CNIC/NICOP
                                        </label>
                                        <input name="cnic" value="{{$prisoner->cnic}}" placeholder="XXXXX-XXXXXXX-X" maxlength="15" class="cnic_mask appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-cnic" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="date_of_birth">
                                            Date of birth
                                        </label>
                                        <input name="date_of_birth"  value="{{$prisoner->date_of_birth}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="date_of_birth" type="date">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="province">
                                            Province
                                        </label>
                                        <select name="provinces" id="province" class=" select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::provinces() as $item)
                                                <option value="{{$item}}" @if($prisoner->provinces == $item) selected @endif >{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                </div>

                                <div class="-mx-3 md:flex mb-3">


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="district">
                                            District
                                        </label>
                                        <select name="district" id="district" class="select2 appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                            <option value="" selected="">Please Select</option>
                                            @foreach(\App\Models\Prisoner::districts() as $item)
                                                <option value="{{$item}}" @if($prisoner->district == $item) selected @endif >{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="tehseel">
                                            Tehseel
                                        </label>
                                        <input name="tehseel"  value="{{$prisoner->tehseel}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="tehseel" type="text">
                                    </div>
                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="muhallah_town">
                                            Muhallah / Town
                                        </label>
                                        <input name="muhallah_town"  value="{{$prisoner->muhallah_town}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="muhallah_town" type="text">
                                    </div>

                                    <div class="md:w-1/2 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="contact_no_in_pakistan">
                                            Contact no in Pakistan
                                        </label>
                                        <input name="contact_no_in_pakistan"  value="{{$prisoner->contact_no_in_pakistan}}"  class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="contact_no_in_pakistan" type="text">
                                    </div>

                                </div>

                                {{--                                @livewire('shifting-date')--}}


                                <div class="-mx-3 md:flex mb-3">
                                    <div class="md:w-1/4 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="photo_1">
                                            Photo
                                        </label>
                                        @if(!empty($prisoner->photo))
                                            <a href="{{Storage::url($prisoner->photo)}}" target="_blank" class="text-blue-700 hover:underline">View Existing Attachment</a>
                                        @endif
                                        <input name="photo_1" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="photo_1" type="file">
                                    </div>
                                    <div class="md:w-1/4 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="passport_1">
                                            Passport
                                        </label>
                                        @if(!empty($prisoner->passport))
                                            <a href="{{Storage::url($prisoner->passport)}}" target="_blank" class="text-blue-700 hover:underline">View Existing Attachment</a>
                                        @endif
                                        <input name="passport_1" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="passport_1" type="file">
                                    </div>
                                    <div class="md:w-1/4 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="iqama_1">
                                            Iqama
                                        </label>
                                        @if(!empty($prisoner->iqama))
                                            <a href="{{Storage::url($prisoner->iqama)}}" target="_blank" class="text-blue-700 hover:underline">View Existing Attachment</a>
                                        @endif
                                        <input name="iqama_1" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="iqama_1" type="file">
                                    </div>
                                    <div class="md:w-1/4 px-3">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="other_1">
                                            Other
                                        </label>
                                        @if(!empty($prisoner->other))
                                            <a href="{{Storage::url($prisoner->other)}}" target="_blank" class="text-blue-700 hover:underline">View Existing Attachment</a>
                                        @endif
                                        <input name="other_1" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="other_1" type="file">
                                    </div>
                                </div>

                                <div style="float: right" class="mt--1">

                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold
                                    text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900
                                    focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                                        Update
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
