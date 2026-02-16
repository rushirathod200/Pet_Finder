<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Add Pet for Adoption - Dog Adoption Platform</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b93ee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101a22",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111518] dark:text-gray-100 antialiased h-screen flex overflow-hidden">
@include('partials.dashboard-sidebar', ['user' => $user, 'activeNav' => 'listapet'])
<main class="flex-1 h-full overflow-y-auto bg-background-light dark:bg-background-dark scroll-smooth">
<div class="max-w-[1000px] mx-auto p-6 md:p-8 flex flex-col gap-6 pb-20">
<div class="flex flex-wrap gap-2 items-center">
<a class="text-[#617789] dark:text-gray-400 text-sm font-medium hover:text-primary transition-colors" href="/">Home</a>
<span class="text-[#617789] dark:text-gray-500 text-sm font-medium">/</span>
<a class="text-[#617789] dark:text-gray-400 text-sm font-medium hover:text-primary transition-colors" href="/profile">Dashboard</a>
<span class="text-[#617789] dark:text-gray-500 text-sm font-medium">/</span>
<span class="text-[#111518] dark:text-gray-200 text-sm font-medium">Add Pet</span>
</div>
<div class="flex flex-col gap-2">
<h1 class="text-[#111518] dark:text-white tracking-tight text-3xl font-bold leading-tight">List a new pet for adoption</h1>
<p class="text-[#617789] dark:text-gray-400 text-base">Fill in the details below to create a comprehensive profile for the dog. The more details you provide, the better the match!</p>
</div>
<div class="mt-6">
<div class="w-full bg-white dark:bg-[#1a2632] rounded-xl border border-[#dbe1e6] dark:border-gray-700 shadow-sm overflow-hidden">
<form class="divide-y divide-[#e6e8eb] dark:divide-gray-700" method="post" action="/listapet" enctype="multipart/form-data">
    @csrf
@if (session('success'))
<div class="m-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>
@endif
@if ($errors->any())
<div class="m-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">{{ $errors->first() }}</div>
@endif
<div class="p-6 md:p-8 flex flex-col gap-6" id="pet-info">
<div class="flex items-center gap-3 mb-2">
<span class="material-symbols-outlined text-primary text-2xl">pets</span>
<h2 class="text-xl font-bold text-[#111518] dark:text-white">Pet Information</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="col-span-1 md:col-span-2">
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Pet Name</label>
<input name="petname" class="Pet Name w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary dark:focus:ring-primary/50 transition-colors" placeholder="e.g. Buddy" type="text"/>
</div>
<div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Breed</label>
<select name="breed"  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary transition-colors appearance-none">
<option value="na">Select Breed</option>
<option value="mixed breed"> Mixed Breed</option>
<option value="labrador retriever">Labrador Retriever</option>
<option value="german shepherd">German Shepherd</option>
<option value="golden retriever">Golden Retriever</option>
<option value="bulldog">Bulldog</option>
</select>
</div>
<div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Age</label>
<div class="flex gap-2">
<input name="age" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary transition-colors age " placeholder="3" type="number"/>
<select class="w-24 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-2 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary transition-colors">
<option>Years</option>
<option>Months</option>
</select>
</div>
</div>
<div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Size</label>
<select name="size"  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary transition-colors appearance-none">
<option value="na">Select Size</option>
<option value="small ">Small (0-25 lbs)</option>
<option value="medium">Medium (26-60 lbs)</option>
<option value="large">Large (61-100 lbs)</option>
<option value="xlarge">Extra Large (101-150 lbs)</option>
</select>
</div>
<div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Gender</label>
<div class="flex gap-4">
<label class="flex-1 flex items-center justify-center gap-2 p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary dark:has-[:checked]:bg-primary/20 transition-all">
<input class="text-primary focus:ring-primary border-gray-300" name="gender" value="Male" type="radio"/>
<span class="text-sm font-medium">Male</span>
</label>
<label class="flex-1 flex items-center justify-center gap-2 p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary dark:has-[:checked]:bg-primary/20 transition-all">
<input class="text-primary focus:ring-primary border-gray-300" name="gender" value="Female" type="radio"/>
<span class="text-sm font-medium">Female</span>
</label>
</div>
</div>
<div class="col-span-1 md:col-span-2">
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Description</label>
<textarea name="Description" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary transition-colors" placeholder="Tell us about the dog's personality, history, and what makes them special..." rows="4"></textarea>
<p class="text-xs text-gray-500 mt-1 text-right">0/500 characters</p>
</div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Location</label><br>
<input name="Location" class="Location w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary dark:focus:ring-primary/50 transition-colors" placeholder="e.g. Rajkot,guj" type="text"/>
</div>
</div>
<div class="p-6 md:p-8 flex flex-col gap-6" id="medical">
<div class="flex items-center gap-3 mb-2">
<span class="material-symbols-outlined text-primary text-2xl">medical_services</span>
<h2 class="text-xl font-bold text-[#111518] dark:text-white">Medical History</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-3">Spayed / Neutered?</label>
<div class="flex items-center gap-6">
<label class="flex items-center gap-2 cursor-pointer">
<input class="size-4 text-primary focus:ring-primary border-gray-300" name="Yes" type="radio"/>
<span class="text-sm text-gray-700 dark:text-gray-300">Yes</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input class="size-4 text-primary focus:ring-primary border-gray-300" name="No" type="radio"/>
<span class="text-sm text-gray-700 dark:text-gray-300">No</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input class="size-4 text-primary focus:ring-primary border-gray-300" name="Unknown" type="radio"/>
<span class="text-sm text-gray-700 dark:text-gray-300">Unknown</span>
</label>
</div>
</div>
<div>
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-3"></label>
<div class="grid grid-cols-2 gap-2">

</div>
</div>
<div class="col-span-1 md:col-span-2">
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300 mb-2">Medical Conditions / Needs</label>
<input name="MedicalConditions" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 px-4 py-2.5 text-[#111518] dark:text-white focus:border-primary focus:ring-primary transition-colors" placeholder="e.g. Needs daily joint supplement, slight limp on left leg" type="text"/>
</div>
</div>
</div>
<div class="p-6 md:p-8 flex flex-col gap-6" id="behavior">
<div class="flex items-center gap-3 mb-2">
<span class="material-symbols-outlined text-primary text-2xl">psychology</span>
<h2 class="text-xl font-bold text-[#111518] dark:text-white">Behavioral Traits</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="col-span-1 md:col-span-2">

</div>
<div class="space-y-4">
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-gray-700 dark:text-gray-300">Good with Kids?</span>
<select class="w-32 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-2 py-1.5">
<option>Yes</option>
<option>No</option>
<option>Unknown</option>
</select>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-gray-700 dark:text-gray-300">Good with Dogs?</span>
<select class="w-32 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-2 py-1.5">
<option>Yes</option>
<option>No</option>
<option>Unknown</option>
</select>
</div>
<div class="flex justify-between items-center">
<span class="text-sm font-medium text-gray-700 dark:text-gray-300">Good with Cats?</span>
<select class="w-32 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-2 py-1.5">
<option>Yes</option>
<option>No</option>
<option>Unknown</option>
</select>
</div>
</div>
</div>
</div>
<div class="p-6 md:p-8 flex flex-col gap-6" id="photos">
<div class="flex items-center gap-3 mb-2">
<span class="material-symbols-outlined text-primary text-2xl">add_a_photo</span>
<h2 class="text-xl font-bold text-[#111518] dark:text-white">Photos &amp; Videos</h2>
</div>
<div
  class="w-full h-48 border-2 border-dashed border-gray-300 dark:border-gray-600 
         rounded-xl bg-gray-50 dark:bg-gray-800 flex flex-col items-center 
         justify-center gap-2 cursor-pointer hover:bg-gray-100 
         dark:hover:bg-gray-700 transition-colors"
  onclick="document.getElementById('fileInput').click()"
>

  <span class="material-symbols-outlined text-gray-400 text-4xl">
    cloud_upload
  </span>

  <p class="text-sm font-bold text-gray-600 dark:text-gray-400">
    Click to upload or drag and drop
  </p>

  <p class="text-xs text-gray-500">
    SVG, PNG, JPG or GIF (max. 5MB)
  </p>

  <!-- Hidden file input -->
  <input
    type="file"
    id="fileInput"
    name="Photos"
    class="hidden"
    accept="image/*"
    onchange="handleFile(this)"
  />
</div>


<div class="p-6 md:p-8 flex flex-col gap-6" id="requirements">
<div class="flex items-center gap-3 mb-2">
<span class="material-symbols-outlined text-primary text-2xl">assignment</span>
<h2 class="text-xl font-bold text-[#111518] dark:text-white">Adoption Requirements</h2>
</div>
<div class="space-y-4">
<label class="block text-sm font-bold text-[#111518] dark:text-gray-300">Requirements for Adoption</label>
<label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
<input name="FencedYardRequired"  class="size-4 rounded border-gray-300 text-primary focus:ring-primary" type="checkbox"/>
<div class="flex flex-col">
<span class="text-sm font-bold text-[#111518] dark:text-white">Fenced Yard Required</span>
<span class="text-xs text-gray-500">Dog needs space to run securely</span>
</div>
</label>
<label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
<input name="VetRequired" class="size-4 rounded border-gray-300 text-primary focus:ring-primary" type="checkbox"/>
<div class="flex flex-col">
<span class="text-sm font-bold text-[#111518] dark:text-white">Home Visit Required</span>
<span class="text-xs text-gray-500">Adopter agrees to a home inspection</span>
</div>
</label>
<label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
<input name="NoChildrenUnder5" class="size-4 rounded border-gray-300 text-primary focus:ring-primary" type="checkbox"/>
<div class="flex flex-col">
<span class="text-sm font-bold text-[#111518] dark:text-white">No Children Under 5</span>
<span class="text-xs text-gray-500">Dog may be too energetic for small kids</span>
</div>
</label>
</div>
<div>
</div>
</div>
<div class="p-6 md:p-8 bg-gray-50 dark:bg-gray-800/50 flex flex-col sm:flex-row items-center justify-between gap-4">
<button class="text-gray-500 font-bold text-sm hover:text-gray-800 dark:hover:text-gray-200" type="button">Cancel</button>
<div class="flex gap-4 w-full sm:w-auto">
<button type="submit" class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg bg-primary hover:bg-blue-600 text-white font-bold text-sm shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5">Create Listing</button>
</div>
</div>
</form>
</div>
</div>
</div>
</main>
<script>
function handleFile(input) {
  const label = input.closest('div')?.querySelector('p.text-sm.font-bold');
  if (!label) return;
  if (input.files && input.files.length > 0) {
    label.textContent = input.files[0].name;
  } else {
    label.textContent = 'Click to upload or drag and drop';
  }
}
</script>
</body></html>
