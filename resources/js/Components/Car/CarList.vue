<script setup>
import CarCard from "@/Components/Car/CarCard.vue";

defineProps({
    cars: {
        type: Array,
        default: () => []
    }
})
</script>


<template>
    <div class="min-w-full min-h-full">
        <h2 class="text-2xl font-bold mb-4">차량 목록</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <CarCard v-for="car in cars" :key="car.id" :car="car" />
        </div>
    </div>
</template>



