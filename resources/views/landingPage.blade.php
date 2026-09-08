<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bissmilah Mari</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFCF8]">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
  <nav class="w-full fixed top-0 z-100 flex flex-row h-max p-4 bg-[#A6A6A6] backdrop-blur-2xl border-b border-gray-200 items-center justify-between">
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
      <label for="search" class="sr-only">Search</label>
      <input type="text" class="rounded-full px-4 py-1 border border-[#000]" placeholder="Cari Teman...">
    </form>
    </div>

    <div class="flex flex-row gap-6 items-center justify-center">
      <a class="text-[#000] px-4 py-2" href="#">Chat</a>
      <a class="text-[#000] px-4 py-2" href="#gameFavorite">Game Favorite</a>
      <a class="text-[#000] px-4 py-2" href="#">Home</a>
    </div>

    <div class="flex flex-row gap-4 items-center justify-center">
      <a class="text-gray-950 font-bold px-4 py-2" href="register">Sign In</a>
      <a class="text-gray-950 font-bold px-4 py-2" href="login">Login</a>
    </div>
  </nav>

  <div class="flex flex-row items-center justify-center flex-col gap-100 mt-55 mb-60 p-8 h-fill bg-[#FDFCF8]">
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

<section id="gameFavorite" class="pt-20">
<!-- Bagian Judul -->
<div class="flex flex-col items-center justify-center gap-1 p-8 bg-[#FDFCF8]">
    <h1 class="text-3xl font-bold">Pilih Game Favoritmu</h1>
    <br>
    <p>MABAR mendukung empat game kompetitif terbesar saat ini. Temukan partner bermain</p>
    <p>untuk setiap platform favoritmu.</p>
</div>

<!-- Container Pembungkus Card (Berjejer 4 Game di Tengah dengan Efek Hover) -->
<div class="max-w-8xl mx-auto flex flex-wrap justify-center gap-10 p-20 bg-[#FDFCF8]">
    
    <!-- Card 1: Mobile Legends -->
    <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <a href="#">
            <img class="w-full h-auto" src="{{ asset('images/Game/ML.png') }}" alt="Mobile Legends" />
        </a>
        <div class="p-9 text-left">
            <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                <span>MOBA 5V5</span>
                <span>Ranked / Classic</span>
            </div>
            <a href="#">
                <h5 class="text-base font-bold tracking-tight text-black">Mobile Legends</h5>
            </a>
        </div>
    </div>

    <!-- Card 2: PUBG Mobile -->
    <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <a href="#">
            <img class="w-full h-auto" src="{{ asset('images/Game/pubg.png') }}" alt="PUBG Mobile" />
        </a>
        <div class="p-9 text-left">
            <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                <span>BATTLE ROYALE</span>
                <span>Squad / Duo</span>
            </div>
            <a href="#">
                <h5 class="text-base font-bold tracking-tight text-black">PUBG Mobile</h5>
            </a>
        </div>
    </div>

    <!-- Card 3: Valorant -->
    <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <a href="#">
            <img class="w-full h-auto" src="{{ asset('images/Game/valorant.png') }}" alt="Valorant" />
        </a>
        <div class="p-9 text-left">
            <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                <span>TACTICAL SHOOTER</span>
                <span>Competitive 5v5</span>
            </div>
            <a href="#">
                <h5 class="text-base font-bold tracking-tight text-black">Valorant</h5>
            </a>
        </div>
    </div>

    <!-- Card 4: FC Mobile -->
    <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
        <a href="#">
            <img class="w-full h-auto" src="{{ asset('images/Game/FC.png') }}" alt="FC Mobile" />
        </a>
        <div class="p-9 text-left">
            <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                <span>SPORTS SIMULATION</span>
                <span>1v1 Head-to-Head</span>
            </div>
            <a href="#">
                <h5 class="text-base font-bold tracking-tight text-black">FC Mobile</h5>
            </a>
        </div>
    </div>

  </div>
  </section>
    <!-- Kategori Game -->
<div class="flex flex-col gap-1 p-30 bg-[#FDFCF8]">
    <h1 class="text-3xl font-bold">Mobile legends</h1>
</div>

</body>

</html>