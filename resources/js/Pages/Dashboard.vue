<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, usePage} from '@inertiajs/vue3';

const page = usePage()

const reservations = page.props.reservations

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
            <div>
                <h2 class="text-2xl font-bold mb-4">예약 리스트</h2>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">


                    <div v-for="reservation in reservations" :key="reservation.id" class="mb-4 p-4 bg-gray-100 rounded-md">
                        <h3 class="text-lg font-semibold">{{ reservation.start_date }} - {{ reservation.end_date }}</h3>
                        <p class="text-gray-500">차량: {{ reservation.car.model }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

