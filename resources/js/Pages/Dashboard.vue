<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";

const page = usePage();

const reservations = page.props.reservations;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

            <div>
                <h2 class="text-2xl font-bold mb-4">예약 리스트</h2>
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- 예약 리스트 표시 -->
                    <div v-if="reservations.length === 0" class="p-4 text-gray-500">
                        예약이 없습니다.
                    </div>
                    <div v-else>
                        <div
                            v-for="reservation in reservations"
                            :key="reservation.id"
                            class="mb-4 p-4 rounded-md bg-white h-full flex flex-row justify-between"
                        >
                            <div>
                                <h3 class="text-lg font-semibold mb-2">
                                    {{ dayjs(reservation.start_date).format('YYYY년 MM월 DD일') }} - {{ dayjs(reservation.end_date).format('YYYY년 MM월 DD일') }}
                                </h3>
                                <p class="text-gray-500 font-semibold mb-2">
                                    픽업 시간 : {{dayjs(reservation.pickup_time).format('HH시 mm분')}}
                                </p>
                                <p class="text-gray-500 mb-2">
                                    결제 : {{reservation.pay_method === "cash"?"현금":"카드"}}
                                </p>
                                <p class="text-gray-500 mb-2">차량: {{ reservation.car.model }}</p>
                                <p class="text-gray-500">총 가격: {{ reservation.total_price.toLocaleString('ko') }}원</p>
                            </div>


                            <img :src="reservation.car.imageUrl" alt="car_image">

                        </div>
                    </div>
                </div>
            </div>
    </AuthenticatedLayout>
</template>
