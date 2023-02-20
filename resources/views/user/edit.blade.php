<x-app-layout>

    @section('custom_css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Nastaliq+Urdu">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
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

                            <form action="{{route('user.update', $user->id)}}" class="mb-6" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="bg-white rounded px-8 pt-6 pb-8 ">
                                    <div class="-mx-3 md:flex mb-3">



                                        <div class="md:w-1/2 px-3">
                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="name">
                                                Name
                                            </label>
                                            <input name="name" value="{{$user->name}}" required class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="name" type="text">
                                        </div>
                                        <div class="md:w-1/2 px-3">
                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="email">
                                                Email
                                            </label>
                                            <input name="email" value="{{$user->email}}"  required class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="email" type="email">
                                        </div>

                                        <div class="md:w-1/2 px-3">
                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="password">
                                                Password
                                            </label>
                                            <input name="password" required class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="password" type="password">
                                        </div>

                                        <div class="md:w-1/2 px-3">
                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="role">
                                                Role
                                            </label>
                                            <select name="role" required id="role" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                                <option value="None" selected>None</option>
                                                <option value="Super-Admin" @if($user->roles->pluck('name')[0] == "Super-Admin") selected @endif>Super Admin</option>
                                                <option value="admin" @if($user->roles->pluck('name')[0] == "admin") selected @endif >Admin</option>
                                                <option value="data entry" @if($user->roles->pluck('name')[0] == "data entry") selected @endif >Data Entry</option>
                                            </select>
                                        </div>


                                    </div>

                                    <div style="float: right" class="mt--1">
                                        <button class="bg-blue-500 hover:bg-blue-400
                                    text-white font-bold py-2 px-4 border-b-4
                                    border-blue-700 hover:border-blue-500 rounded">
                                            <span>Update</span>
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
