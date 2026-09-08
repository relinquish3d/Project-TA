<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bissmilah Mari</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#D9D9D9]">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
  <nav class="w-full flex flex-row h-max p-4 bg-[#A6A6A6] backdrop-blur-2xl border-b border-gray-200 items-center justify-between">
    <div class="w-max flex flex-row gap-4 items-center">
      <a href="#">
        <div class="flex flex-row gap-2 items-center justify-center">
          <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-10 h-10 object-contain">
              <pattern id="pattern0_178_34" patternContentUnits="objectBoundingBox" width="1" height="1">
                <use xlink:href="#image0_178_34" transform="matrix(0.00309962 0 0 0.00353357 -0.00368855 0)" />
              </pattern>
            </defs>
          </svg>
      </a>
      <p class="text-[#000] font-bold">
        Play All
      </p> 
    </div>

    <form>
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
      class="lucide lucide-search absolute top-1/2 -translate-y-2 left-38 h-4 w-4"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
      <label for="search" class="sr-only">Search</label>

      <input type="text" class="rounded-full px-4 py-1 border border-[#000]" placeholder="Cari Teman...">
    </form>

    <div class="flex flex-row gap-6 items-center justify-center">

      <a class="text-[#000] px-4 py-2" href="#">Chat</a>
      <a class="text-[#000] px-4 py-2" href="#">Game Favorite</a>
      <a class="text-[#000] px-4 py-2" href="#">Home</a>
    </div>

    <div class="flex flex-row gap-4 items-center justify-center">
      <a class="text-gray-950 font-bold px-4 py-1 hover:underline transition-colors duration-150" href="register">Sign In</a>
      <a class="text-gray-950 font-bold px-4 py-1 hover:underline transition-colors duration-150" href="login">Login</a>
    </div>
  </nav>

  <div class="flex flex-row items-center justify-center flex-col gap-70 mt-20 p-8 h-fill bg-[#D9D9D9]">
    <div class=" order-1 flex flex-col w-100">
      <h1 class=" text-5xl w-100 font-bold text-4xl">Temukan Teman Mabar Mu Di Sini</h1>
      <br>
      <p class="rounded-5xl text-md font-light flex-col">
      Bosan bermain sendiri, ingin mendapatkan teman mabar?
      Di sini kamu dapat menemukan teman mabar yang sesuai 
      dengan game yang kamu mainkan
      </p> 
    </div>
    <div class="order-2 flex flex-row items-center justify-right gap-4">
     <img style="border-radius: 7rem;" src="{{ asset('images/game.png') }}" class="drop-shadow-[-18px_10px_3px_#A6A6A6] w-130  block" alt="bayangan kiri">
    </div>
  </div>

  <div class="flex flex-col items-center justify-center gap-4 mt-20 p-8 h-fill bg-[#D9D9D9]">
    <h1 class="text-3xl font-bold">Pilih Game Favoritmu</h1>
    <br>
    <p>MABAR mendukung empat game kompetitif terbesar saat ini. Temukan partner bermain untuk setiap platform favoritmu.</p>    
  </div>
</body>

</html>