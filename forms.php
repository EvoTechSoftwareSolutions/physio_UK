<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Appointment Info</title>
    <meta name="author" content="David Grzyb">
    <meta name="description" content="">

    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

        .font-family-karla {
            font-family: karla;
        }

        .bg-sidebar {
            background: #192537;
        }

        .cta-btn {
            color: #3d68ff;
        }

        .upgrade-btn {
            background: #1947ee;
        }

        .upgrade-btn:hover {
            background: #0038fd;
        }

        .active-nav-link {
            background: #1947ee;
        }

        .nav-item:hover {
            background: #1947ee;
        }

        .account-link:hover {
            background: #3d68ff;
        }
    </style>
</head>

<body class="bg-gray-800 font-family-karla flex">
    <?php
    require_once "./Backend/connection.php";
    if (!isset($_GET["id"])) {
        echo "Something went wrong!";
    } else {
        $id = $_GET["id"];
        $rs = Database::search(
            "SELECT * FROM `appointment` WHERE `id` = ?",
            "s",
            $id
        );
        if (!$rs) {
            echo "Invalid appointment ID";
        } else {
            $row = $rs->fetch_assoc();
    ?>

            <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
                <div class="p-6">
                    <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
                    <!-- <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button> -->
                </div>
                <nav class="text-white text-base font-semibold pt-3">
                    <a href="dashboard.html" class="flex items-center  text-white py-4 pl-6 nav-item">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="appointment.php" class="flex items-center active-nav-link text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                        <i class="fas fa-sticky-note mr-3"></i>
                        Appointment
                    </a>
                    <a href="history.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                        <i class="fas fa-table mr-3"></i>
                        Appointment History
                    </a>
                    <!-- <a href="forms.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-align-left mr-3"></i>
                Forms
            </a> -->
                    <!-- <a href="tabs.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tablet-alt mr-3"></i>
                Tabbed Content
            </a> -->
                    <!-- <a href="calendar.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-calendar mr-3"></i>
                Calendar
            </a> -->
                </nav>
                <!-- <a href="#" class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
            <i class="fas fa-arrow-circle-up mr-3"></i>
            Upgrade to Pro!
        </a> -->
            </aside>

            <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
                <!-- Desktop Header -->
                <header class="w-full items-center bg-gray-800 py-2 px-6 hidden sm:flex">
                    <div class="w-1/2"></div>
                    <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                        <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                            <img src="https://source.unsplash.com/uJ8LNVCBjFQ/400x400">
                        </button>
                        <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                        <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
                            <!-- <a href="#" class="block px-4 py-2 account-link hover:text-white">Account</a>
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Support</a> -->
                            <a href="#" class="block px-4 py-2 account-link hover:text-white">Sign Out</a>
                        </div>
                    </div>
                </header>

                <!-- Mobile Header & Nav -->
                <header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
                    <div class="flex items-center justify-between">
                        <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
                        <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
                            <i x-show="!isOpen" class="fas fa-bars"></i>
                            <i x-show="isOpen" class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Dropdown Nav -->
                    <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
                        <a href="index.html" class="flex items-center active-nav-link text-white py-2 pl-4 nav-item">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                        <a href="blank.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                            <i class="fas fa-sticky-note mr-3"></i>
                            Appointment
                        </a>
                        <a href="tables.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                            <i class="fas fa-table mr-3"></i>
                            Appointment History
                        </a>
                        <a href="forms.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                            <i class="fas fa-align-left mr-3"></i>
                            Forms
                        </a>
                        <!-- <a href="tabs.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-tablet-alt mr-3"></i>
                    Tabbed Content
                </a> -->
                        <!-- <a href="calendar.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-calendar mr-3"></i>
                    Calendar
                </a> -->
                        <!-- <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-cogs mr-3"></i>
                    Support
                </a> -->
                        <!-- <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-user mr-3"></i>
                    My Account
                </a> -->
                        <!-- <a href="#" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Sign Out
                </a> -->
                        <!-- <button class="w-full bg-white cta-btn font-semibold py-2 mt-3 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                    <i class="fas fa-arrow-circle-up mr-3"></i> Upgrade to Pro!
                </button> -->
                    </nav>
                    <!-- <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button> -->
                </header>

                <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
                    <main class="w-full flex-grow p-6">
                        <h1 class="w-full text-3xl text-white pb-6">Appointment details</h1>

                        <div class="flex flex-wrap">
                            <!-- <div class="w-full lg:w-1/2 my-6 pr-0 lg:pr-2">
                        <p class="text-xl pb-6 flex items-center">
                            <i class="fas fa-list mr-3"></i> Contact Form
                        </p>
                        <div class="leading-loose">
                            <form class="p-10 bg-white rounded shadow-xl">
                                <div class="">
                                    <label class="block text-sm text-gray-600" for="name">Name</label>
                                    <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" id="name" name="name" type="text" required="" placeholder="Your Name" aria-label="Name">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600" for="email">Email</label>
                                    <input class="w-full px-5  py-4 text-gray-700 bg-gray-200 rounded" id="email" name="email" type="text" required="" placeholder="Your Email" aria-label="Email">
                                </div>
                                <div class="mt-2">
                                    <label class=" block text-sm text-gray-600" for="message">Message</label>
                                    <textarea class="w-full px-5 py-2 text-gray-700 bg-gray-200 rounded" id="message" name="message" rows="6" required="" placeholder="Your inquiry.." aria-label="Email"></textarea>
                                </div>
                                <div class="mt-6">
                                    <button class="px-4 py-1 text-white font-light tracking-wider bg-gray-900 rounded" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div> -->

                            <div class="w-full  mt-6 pl-0 lg:pl-2">
                                <!-- <p class="text-xl pb-6 flex items-center">
                            <i class="fas fa-list mr-3"></i> Checkout Form
                        </p> -->
                                <div class="leading-loose flex items-center justify-center">
                                    <form class="p-10 bg-white rounded shadow-xl">
                                        <div class="flex flex-row w-full justify-between">
                                            <p class="text-lg text-gray-800 font-medium pb-4">Appointment info</p>
                                            <span class="italic text-gray-700"><?php echo $row['date'];?></span>
                                        </div>
                                        <div class="">
                                            <label class="block text-sm text-gray-600" for="cus_name">Name</label>
                                            <span class="w-full px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name"><?php echo $row['fname']." ". $row["lname"];?></span>
                                        </div>
                                        <div class="">
                                            <label class="block text-sm text-gray-600" for="cus_name">Mobile</label>
                                            <span class="w-full px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name">0767676767</span>
                                        </div>
                                        <div class="">
                                            <label class="block text-sm text-gray-600" for="cus_name">Email</label>
                                            <span class="w-full px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name"><?php echo $row['email'];?></span>
                                        </div>
                                        <div class="mt-2">
                                            <label class=" block text-sm text-gray-600" for="cus_email">Address line 01</label>
                                            <span class="w-full px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name">fghfhf fg hfg h</span>
                                        </div>
                                        <div class="mt-2">
                                            <label class=" block text-sm text-gray-600" for="cus_email">Address line 02</label>
                                            <span class="w-full px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name">fghfhf fg hfg h</span>
                                        </div>
                                        <div class="mt-2">
                                            <label class=" block text-sm text-gray-600" for="cus_email">State</label>
                                            <span class="w-full px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name">fghfhf fg hfg h</span>
                                        </div>
                                        <div class="mt-2">
                                            <label class=" block text-sm text-gray-600" for="cus_email">Post code</label>
                                            <span class="w-full px-5 py-1 text-gray-700 rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name">fghfhf fg hfg h</span>
                                        </div>
                                        <div class="mt-2">
                                            <label class=" block text-sm text-gray-600" for="cus_email">Message</label>
                                            <span class="w-full text-justify px-5 py-1 text-gray-700  rounded" id="cus_name" name="cus_name" type="text" required="" aria-label="Name">Lorem, ipsum dolor sit
                                                amet consectetur adipisicing elit. Amet hic numquam ipsa laborum nam ab
                                                assumenda enim facilis et dolorum. Officia, fugit qui! Assumenda facilis natus
                                                ullam doloremque reiciendis quae. Lorem ipsum dolor sit amet consectetur
                                                adipisicing elit. Temporibus corporis adipisci tenetur impedit sed enim placeat
                                                repudiandae sunt nemo molestiae unde eius ex ea repellendus, quis, vel possimus
                                                delectus obcaecati.</span>
                                        </div>
                                        <!-- <p class="text-lg text-gray-800 font-medium py-4">Payment information</p> -->
                                        <!-- <div class="">
                                    <label class="block text-sm text-gray-600" for="cus_name">Card</label>
                                    <input class="w-full px-2 py-2 text-gray-700 bg-gray-200 rounded" id="cus_name" name="cus_name" type="text" required="" placeholder="Card Number MM/YY CVC" aria-label="Name">
                                </div> -->
                                        <div class="mt-6 flex justify-center flex-row gap-5">
                                            <button class="w-2/5 py-1 font-light tracking-wider text-red-500 border border-red-500 rounded hover:text-white hover:bg-red-500" onclick="decline(<?php echo $row['id'];?>);">Decline</button>
                                            <button class="w-2/5 py-1 text-white font-light tracking-wider bg-blue-800 rounded hover:bg-blue-900" onclick="accept(<?php echo $row['id'];?>);">Accept</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- <p class="pt-6 text-gray-600">
                            Source: <a class="underline" href="https://tailwindcomponents.com/component/checkout-form">https://tailwindcomponents.com/component/checkout-form</a>
                        </p> -->
                            </div>
                        </div>
                    </main>

                    <!-- <footer class="w-full bg-white text-right p-4">
                Built by <a target="_blank" href="https://davidgrzyb.com" class="underline">David Grzyb</a>.
            </footer> -->
                </div>

            </div>


            <!-- AlpineJS -->
            <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
            <!-- Font Awesome -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
            <script src="forms.js"></script>
    <?php
        }
    }
    ?>
</body>

</html>