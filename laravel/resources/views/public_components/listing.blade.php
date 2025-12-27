@props(['title' => 'Featured Properties'])

<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1">
    <div class="max-w-[1280px] mx-auto">
        <h3 class="text-lg sm:text-xl font-bold text-[#121317] mb-4 sm:mb-6">{{ $title }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            {{-- Property Card 1: Villa --}}
            <article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <div class="absolute top-3 left-3 z-10 bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                        New Arrival
                    </div>
                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[20px] block">favorite</span>
                        </button>
                    </div>
                    <img alt="Modern luxury villa with pool at sunset" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6JGLNEeRDULjyz7WFJ4ObKCZsQi74fcb4HMbuJJ5VwEvic_qApCHMX8vJ3Knawdusc02xCJkbMMw3C-ejtmLRxazLDraIoearbjj25VNyASPqtCfC0knpA8JNNVSKy8N5tTAcfsmro8U7Rw5LHMHKXZYoI93JICpm3AQNoW2T-CsfJTCVWbaOHWDSd2KDyz10TInVv0nJFeTbQCWYKnXKBCd7GVHrXGiZeWcgHrUY88JJDScTJ-mIIz7znDdPuVAbtllQISMOXo9j"/>
                    <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
                </div>
                <div class="p-5 flex flex-col gap-3 flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">$ 1,200,000</h3>
                            <h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">Luxury Hillside Villa</h4>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-[#666e85] text-sm">
                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                        <p class="truncate">Beverly Hills, Sunset Blvd</p>
                    </div>
                    <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
                            <span>4 Beds</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
                            <span>5 Baths</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
                            <span>3,500 sqft</span>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-1">
                        <a href="{{ route('property.show', ['id' => 1]) }}" class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors inline-flex items-center justify-center">
                            View Details
                        </a>
                        <button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
                            <img alt="Whatsapp logo icon" class="w-4 h-4 invert brightness-0 grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgPEGUEKjZPSkEHYkFnZ9Gx1JHOB4A1agO_DBYEWBuFwMC36Rd8RmQNnZI_gHOSxcW5jm5j7er5TGrbgjT0sz7NoQN3hgJN7vM_63MQhWoNuxGvHhkJhVwUgUA60YXth8XRgsFWRJCOj--W6_Q7ArnfLQpB8r7x-pvzyq0-DuRKBPv130bg0xhlun76EKVNL9J8LIuP-EyPP6RH-5JiA_PIrkeawFrQ2OCm_azTjM6_kaNnj0ET0fIB7wr692Oty0lpjIh_qdYfCpc"/>
                            WhatsApp
                        </button>
                    </div>
                </div>
            </article>

            {{-- Property Card 2: Apartment --}}
            <article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <div class="absolute top-3 left-3 z-10 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                        Popular
                    </div>
                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[20px] block">favorite</span>
                        </button>
                    </div>
                    <img alt="Interior of a modern city apartment living room" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBT0lApEKfu4W1EpyNu2a-E_KsBSNhZJkLiCjGCYvummR4FC0a1kGMhIRQ5LRKU-4Okg66E-spvIJAXTRoGn1O--Ll6ZfgPHm5SMkYd97HoNw6rJvq09qg9Gw3_EDVQowqjfbmY4wz4d982yZUlvp9T3aSr3Zbvj4OMUC4ACM7mOtXJiaXjfPFOXvnmRg-_qDWdyzbsGcGLlxRckKV-YUWUN8-ekStNCiOYvxjBmX7EFNUFVMooeRqgUBmXYedjKo7lFLvj4oi5GxvT"/>
                </div>
                <div class="p-5 flex flex-col gap-3 flex-1">
                    <div>
                        <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">$ 2,500 <span class="text-sm font-normal text-gray-500">/mo</span></h3>
                        <h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">Skyline City Apartment</h4>
                    </div>
                    <div class="flex items-center gap-1 text-[#666e85] text-sm">
                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                        <p class="truncate">Downtown, 5th Avenue</p>
                    </div>
                    <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
                            <span>2 Beds</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
                            <span>2 Baths</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
                            <span>1,100 sqft</span>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-1">
                        <a href="{{ route('property.show', ['id' => 2]) }}" class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors inline-flex items-center justify-center">
                            View Details
                        </a>
                        <button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
                            <img alt="Whatsapp logo icon" class="w-4 h-4 invert brightness-0 grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfsRkg7mANErfpfOt2Lv0x9EyxbJJR8EeXaXdAFalJjXCSbI4E7MTbmNwOv2WpZhD0p8QQRbLFIt6f98x1xWEIbPVtl1B-3NeV6AIqCHQ1mZh6UQqMKy6x448e68CRI48wu4OvpPElDum8QSlBrSK6L2iVCUICswg0VLMc4PPVvDpZ_o7u2ZIs7-vkIv6hWWbaS5mnOY0QvV3gss355KXNhk3Mb-YZWjeG1Xnx6tI89DfGoZVkIQ3P5W29A7bADevSTPBOzpYqi5ZH"/>
                            WhatsApp
                        </button>
                    </div>
                </div>
            </article>

            {{-- Property Card 3: Commercial --}}
            <article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <div class="absolute top-3 left-3 z-10 bg-teal-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                        Verified
                    </div>
                    <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[20px] block">favorite</span>
                        </button>
                    </div>
                    <img alt="Bright modern corporate office space interior" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1E3fs-gJ1AVhYfRUC8Q2XGAbx_loyUQ5nQjgyZJfjbCJHzOX2-G70B8KZBIY2yoMlh4bPUpTkCEgaB-Ck7NlIXO7UB0F4C-5Od4cTJfNoadxt48BxF4gL3ilfKt_4TB1EPulqr_lxiThvhvgFG_Ymy8U5jAUKcr4xK5EDqq0vIETyRoKpjDFdwdD-6mTRTUbOp68mVQLRD6rhCFQYwQal2IOCkO2Wxx-yFMzAwpyF2WIJTBtHx1UYCwcNef2Lsjhde8z3t0QHxQnK"/>
                </div>
                <div class="p-5 flex flex-col gap-3 flex-1">
                    <div>
                        <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">$ 15,000 <span class="text-sm font-normal text-gray-500">/mo</span></h3>
                        <h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">Tech Park Office Space</h4>
                    </div>
                    <div class="flex items-center gap-1 text-[#666e85] text-sm">
                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                        <p class="truncate">Silicon Valley, Innovation Drive</p>
                    </div>
                    <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">domain</span>
                            <span>Commercial</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">meeting_room</span>
                            <span>5 Cabins</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
                            <span>2,000 sqft</span>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-1">
                        <a href="{{ route('property.show', ['id' => 3]) }}" class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors inline-flex items-center justify-center">
                            View Details
                        </a>
                        <button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
                            <img alt="Whatsapp logo icon" class="w-4 h-4 invert brightness-0 grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYqWWB9wOKmYJr--ShfsGDmtb2rzJmUpSe_JUlvPVmIW8-4oArP11gGH8iNh96Opz7O-D5d7IZWU5wx0SiCy9h_hoXTy949M-hHP9bIkegNN50H5Eow6ajyG5YlVfCWNK6b4ajh_XErxxgDqm2L6DXhxfj-v23gy3pbZ2KuFZPEbY8qkJoA0LgOpX9DJr_1uWyy4p9CEs3Q4kTWSVMeGtqESCILFZ4MV7o3ZsmM3O6BN5TNw0U9bZSGNMKLL1tEO7VaZ-xOjTnD80o"/>
                            WhatsApp
                        </button>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>