<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps(["car"]);

</script>


<template>
    <div class="bg-white p-4 rounded-md shadow-md flex flex-row justify-between items-start">
        <div class="flex flex-col justify-between h-full">
            <div>
                <h3 class="text-lg font-semibold mb-2">{{ car.company }} {{ car.model }}</h3>
                <p
                    :class="{'text-green-600': car.status === 'available','text-red-600': car.status !== 'available', }"
                    class="block text-gray-600 text-sm font-medium mb-2">
                    {{
                        car.status === 'available' ? '예약가능' : '예약불가'
                    }}
                </p>
            </div>

            <Link :href="route('show', car.id)">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">
                    상세보기
                </button>
            </Link>
        </div>

        <img :src="car.imageUrl" alt="Car Image" class="w-32 h-32 object-contain mt-2">
    </div>
</template>



