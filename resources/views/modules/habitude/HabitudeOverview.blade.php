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

    <h1 class="font-bold text-2xl text-center">Section Module Habitude 🏆</h1>

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


    <div class="add-projet mb-8">
        <h2 class="text-lg font-bold mb-2">Ajouter une habitude :</h2>
        <form id="form-hab" action="{{ route("store_habitude") }}" method="POST" class="flex flex-col space-y-4">
            @csrf
            <!-- Ajout du jeton CSRF pour la sécurité -->
            <label for="livre_name" class="font-bold">Nom de l'habitude :</label>
            <input type="text" id="habitude_name" name="habitude_name" required
                   class="border border-gray-300 rounded-md px-3 py-2 w-1/2  focus:outline-none focus:border-blue-500" />

<div class="flex-row flex">


            <div class="day lundi">

                <div class="btn-container text-center">
                <button type="button" class="btn-hab text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">Lundi</button>
                <input type="hidden" name="day_0" id="day_0" value="0"/>
                </div>

                <div class="time-container hidden  p-5 ">


                <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="lundi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                            <input name="lundi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                <input type="hidden" name="day_1" id="day_1" value="0"/>
                </div>

                <div class="time-container hidden  p-5">


                    <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="mardi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                                <input name="mardi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                <input type="hidden" name="day_2" id="day_2" value="0"/>
                </div>

                <div class="time-container hidden  p-5">


                    <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="mercredi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                            <input name="mercredi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                <input type="hidden" name="day_3" id="day_3" value="0"/>
                </div>

                <div class="time-container hidden  p-5">


                    <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="jeudi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                            <input name="jeudi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                <input type="hidden" name="day_4" id="day_4" value="0"/>
                </div>

                <div class="time-container hidden  p-5">


                    <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="vendredi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                            <input name="vendredi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                <input type="hidden" name="day_5" id="day_5" value="0"/>
                </div>

                <div class="time-container hidden  p-5">


                    <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="samedi-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                            <input name="samedi-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                <input type="hidden" name="day_6" id="day_6" value="0"/>
                </div>

                <div class="time-container hidden  p-5">


                    <div class="timepicker">




                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heure de début :</label>
                        <div class="flex">
                            <input name="dimanche-start" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="08:00" required>
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
                            <input name="dimanche-stop" type="time" id="time" class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 leading-none focus:ring-blue-500 focus:border-blue-500 block flex-1 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="00:00" max="23:59" value="12:00" required>
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
                   class="bg-blue-500 text-white font-bold px-4 py-2 w-1/2 rounded cursor-pointer hover:bg-blue-600">Créer l'habitude</button>
        </form>
    </div>

    <h3 class="text-lg font-bold mb-4">Liste des habitudes en cours</h3>


    <div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Boucle pour afficher les dossiers -->
        @foreach ($habitudes as $habitude)
            <div class="task-card bg-white p-4 rounded-md shadow-md">
                <a  href="{{ route("habitude_view", $habitude->id) }}" class="text-blue-500 hover:underline">
                    <span class="font-bold justify-center">{{ $habitude->name }}</span>
                </a>

                <form action="{{ route("toggle_habitude") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $habitude->id }}" />
                    <input type="submit" value="Mettre l'habitude en pause"
                           class="bg-red-500 text-white font-bold px-4 py-2  rounded cursor-pointer hover:bg-red-600" />
                    @csrf
                </form>

                <form action="{{ route("delete_habitude") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $habitude->id }}" />
                    <input type="submit" value="Supprimer l'habitude"
                           class="bg-red-500 text-white font-bold px-4 py-2  rounded cursor-pointer hover:bg-red-600" />
                    @csrf
                </form>
                <!-- Autres détails du dossier si nécessaire -->
            </div>
        @endforeach
    </div>

    <h3 class="text-lg font-bold mb-4">Liste des habitudes  désactivés</h3>

    <div class="folders grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Boucle pour afficher les dossiers -->
        @foreach ($habitudes_not as $habitude_nt)
            <div class="task-card bg-white p-4 rounded-md shadow-md">
                <a href="{{ route("habitude_view", $habitude_nt->id) }}" class="text-blue-500 hover:underline">
                    <h3 class="font-bold">{{ $habitude_nt->name }}</h3>
                </a>
                <form action="{{ route("delete_project") }}" method="POST">
                    <input name="project_id" type="hidden" value="{{ $habitude_nt->id }}" />
                    <input type="submit" value="Supprimer le projet"
                           class="bg-red-500 text-white font-bold px-4 py-2 w-1/2 rounded cursor-pointer hover:bg-red-600" />
                    @csrf
                </form>
                <!-- Autres détails du dossier si nécessaire -->
            </div>
        @endforeach
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
           main_container.children[1].classList.remove("block")
           main_container.children[1].classList.add("hidden")
       }
       else{
            child.value = 1;
            // Selection
           btn.classList.replace("from-red-500","from-blue-500")
           btn.classList.replace("via-red-600","via-blue-600")
           btn.classList.replace("to-red-700","to-blue-700")
           main_container.classList.add("border"); // Ajouter la bordure de la div principal
           main_container.children[1].classList.remove("hidden")
           main_container.children[1].classList.add("block")

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
