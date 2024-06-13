@include("includes.header")

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Liste des habitudes</title>
    <link rel="stylesheet" href="{{ asset("css/folder/Overview.css") }}" />
    <!-- Assurez-vous d'avoir le lien vers votre fichier CSS -->
    <link rel="stylesheet" href="{{ asset("css/category.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/notification/notification.css") }}" />
</head>

<div id="notification" class="notification">
    <div class="progress"></div>
</div>

<body class="bg-gray-100">

<div class="container mx-auto">

    <h1 class="font-bold text-2xl text-center mb-5">Editeur d'Habitude {{$habitude->name}} : 🏆</h1>


    @if ($errors->any())
        <div class="alert alert-danger">
            <h2 class="text-red-500">Il y a eu des erreurs</h2>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="add-projet mb-8 text-center ">
        <form id="form-hab" action="{{ route("update_habitude") }}" method="POST" class="flex flex-col items-center space-y-4" >

            <input type="hidden" name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}"/>
            <input type="hidden" name="habit_id" value="{{$habitude->id}}"/>

            @csrf
            <div class="flex-row flex">


                <div class="day lundi">

                    <div class="btn-container text-center">
                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Lundi</button>
                        <input type="hidden" name="day_0" id="day_0" value="{{array_key_exists(0,$habits_possede) ? 1 : 0}}"/>
                    </div>



                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="lundi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[0]["start"]) ? $habits_possede[0]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <form class="max-w-[8.5rem] mx-auto">
                                <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                                <div class="flex">
                                    <input name="lundi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[0]["stop"]) ? $habits_possede[0]["stop"] : "12:00"}}" required>
                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                                </div>
                            </form>




                        </div>

                    </div>


                </div>

                <div class="day mardi">
                    <div class="btn-container text-center">


                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Mardi</button>
                        <input type="hidden" name="day_1" id="day_1" value="{{array_key_exists(1,$habits_possede) ? 1 : 0}}"/>
                    </div>

                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="mardi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[1]["start"]) ? $habits_possede[1]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                            <div class="flex">
                                <input name="mardi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[1]["stop"]) ? $habits_possede[0]["stop"] : "12:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                    </div>
                </div>

                <div class="day mercredi">

                    <div class="btn-container text-center">


                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Mercredi</button>
                        <input type="hidden" name="day_2" id="day_2" value="{{array_key_exists(2,$habits_possede) ? 1 : 0}}"/>
                    </div>

                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="mercredi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[2]["start"]) ? $habits_possede[2]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                            <div class="flex">
                                <input name="mercredi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[2]["stop"]) ? $habits_possede[2]["stop"] : "12:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                    </div>

                </div>

                <div class="day jeudi">
                    <div class="btn-container text-center">
                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Jeudi</button>
                        <input type="hidden" name="day_3" id="day_3" value="{{array_key_exists(3,$habits_possede) ? 1 : 0}}"/>
                    </div>

                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="jeudi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[3]["start"]) ? $habits_possede[3]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                            <div class="flex">
                                <input name="jeudi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[3]["stop"]) ? $habits_possede[3]["stop"] : "12:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                    </div>
                </div>

                <div class="day vendredi">
                    <div class="btn-container text-center">


                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Vendredi</button>
                        <input type="hidden" name="day_4" id="day_4" value="{{array_key_exists(4,$habits_possede) ? 1 : 0}}"/>
                    </div>

                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="vendredi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[4]["start"]) ? $habits_possede[4]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                            <div class="flex">
                                <input name="vendredi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[4]["stop"]) ? $habits_possede[4]["stop"] : "12:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                    </div>

                </div>

                <div class="day samedi">

                    <div class="btn-container text-center">


                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Samedi</button>
                        <input type="hidden" name="day_5" id="day_5" value="{{array_key_exists(5,$habits_possede) ? 1 : 0}}"/>
                    </div>

                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="samedi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[5]["start"]) ? $habits_possede[5]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                            <div class="flex">
                                <input name="samedi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[5]["stop"]) ? $habits_possede[5]["stop"] : "12:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                    </div>
                </div>

                <div class="day dimanche">
                    <div class="btn-container text-center">


                        <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Dimanche</button>
                        <input type="hidden" name="day_6" id="day_6" value="{{array_key_exists(6,$habits_possede) ? 1 : 0}}"/>
                    </div>

                    <div class="time-container priority_none  p-5" ">


                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                            <div class="flex">
                                <input name="dimanche-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[6]["start"]) ? $habits_possede[6]["start"] : "08:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                        <div class="timepicker">




                            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de fin :</label>
                            <div class="flex">
                                <input name="dimanche-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="{{isset($habits_possede[6]["stop"]) ? $habits_possede[6]["stop"] : "12:00"}}" required>
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-s-0 border-s-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
            </svg>
        </span>
                            </div>




                        </div>

                    </div>

                </div>
            </div>


            <button id="submit-form-hab"  type="submit"
                    class="bg-blue-500 text-white font-bold px-4 py-2 w-1/2 rounded cursor-pointer hover:bg-blue-600">Modifier l'habitude</button>
        </form>
    </div>

</div>
</body>

<script src="{{ asset("js/notification.js") }}"></script>

<script>
    @if (session("success"))
    showNotification("{{ session("success") }}", 'success');
    @elseif (session("failure"))
    showNotification("{{ session("success") }}", 'failure');
    @endif
</script>


<script>
    let btn_hab = document.getElementsByClassName("btn-hab");




    Array.prototype.forEach.call(btn_hab, (btn) => {

        let div_parent = btn.parentElement;
        let child = div_parent.children[1];
        let main_container = btn.parentElement.parentElement;
        if(child.value === '1'){
            btn.classList.replace("from-red-500","from-blue-500")
            btn.classList.replace("via-red-600","via-blue-600")
            btn.classList.replace("to-red-700","to-blue-700")
            main_container.classList.add("border"); // Ajouter la bordure de la div principal
            main_container.children[1].classList.remove("priority_none")
            main_container.children[1].classList.add("priority_block")

        }
        else{
            btn.classList.replace("from-blue-500","from-red-500")
            btn.classList.replace("via-blue-600","via-red-600")
            btn.classList.replace("to-blue-700","to-red-700")
            main_container.classList.remove("border"); // Enlever la bordure de la div principal
            main_container.children[1].classList.remove("priority_block")
            main_container.children[1].classList.add("priority_none")


        }


        btn.addEventListener('click', () => {
            let div_parent = btn.parentElement;
            let child = div_parent.children[1];
            let main_container = btn.parentElement.parentElement;
            if(child.value === '1'){
                child.value = 0;
                console.log("change")
                btn.classList.replace("from-blue-500","from-red-500")
                btn.classList.replace("via-blue-600","via-red-600")
                btn.classList.replace("to-blue-700","to-red-700")
                main_container.classList.remove("border"); // Enlever la bordure de la div principal
                main_container.children[1].classList.remove("priority_block")
                main_container.children[1].classList.add("priority_none")
            }
            else{
                child.value = 1;
                // Selection
                btn.classList.replace("from-red-500","from-blue-500")
                btn.classList.replace("via-red-600","via-blue-600")
                btn.classList.replace("to-red-700","to-blue-700")
                main_container.classList.add("border"); // Ajouter la bordure de la div principal
                main_container.children[1].classList.remove("priority_none")
                main_container.children[1].classList.add("priority_block")

            }

        });





    });


</script>


<script>
    function validateTimePickers() {
        let days = [
            { day: document.getElementById("day_0"), start: document.querySelector("input[name='lundi-start']"), stop: document.querySelector("input[name='lundi-stop']"), name:"Lundi" },
            { day: document.getElementById("day_1"), start: document.querySelector("input[name='mardi-start']"), stop: document.querySelector("input[name='mardi-stop']"), name:"Mardi" },
            { day: document.getElementById("day_2"), start: document.querySelector("input[name='mercredi-start']"), stop: document.querySelector("input[name='mercredi-stop']"), name:"Mercredi"},
            { day: document.getElementById("day_3"), start: document.querySelector("input[name='jeudi-start']"), stop: document.querySelector("input[name='jeudi-stop']"), name:"Jeudi" },
            { day: document.getElementById("day_4"), start: document.querySelector("input[name='vendredi-start']"), stop: document.querySelector("input[name='vendredi-stop']"),name:"Vendredi" },
            { day: document.getElementById("day_5"), start: document.querySelector("input[name='samedi-start']"), stop: document.querySelector("input[name='samedi-stop']"),name:"Samedi" },
            { day: document.getElementById("day_6"), start: document.querySelector("input[name='dimanche-start']"), stop: document.querySelector("input[name='dimanche-stop']"), name:"Dimanche" }
        ];

        for (let i = 0; i < days.length; i++) {
            if (days[i].day.value === "1") {
                let startTime = days[i].start.value;
                let stopTime = days[i].stop.value;

                if (startTime >= stopTime) {
                    alert(`Pour le jour ${days[i].name}, l'heure de début doit être avant l'heure de fin et elles doivent être différentes.`);
                    return false;
                }
            }
        }

        return true;
    }

</script>

<script>
    document.getElementById("submit-form-hab").addEventListener("click", () => {
        // Verification avant submit

        // 1) Verifier qu'au moins un jour soit selectionné
        let day_0 = document.getElementById("day_0");
        let day_1 = document.getElementById("day_1");
        let day_2 = document.getElementById("day_2");
        let day_3 = document.getElementById("day_3");
        let day_4 = document.getElementById("day_4");
        let day_5 = document.getElementById("day_5");
        let day_6 = document.getElementById("day_6");

// Aucun jour de selectionné
        let condition_1 = day_0.value === "0" && day_1.value === "0" && day_2.value === "0" && day_3.value === "0" && day_4.value === "0" && day_5.value === "0" && day_6.value === "0";
        console.log(condition_1)
        if(condition_1)
            alert("Au moins un jour doit être selectionné");
        else{
            if(validateTimePickers())
                document.getElementById("form-hab").submit();

        }







    })
</script>




</html>

@include("includes.footer")
