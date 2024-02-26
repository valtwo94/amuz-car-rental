<script setup>
import {computed, onMounted, ref, watch, watchEffect} from 'vue';
import {usePage, Link} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import dayjs from "dayjs";
import 'dayjs/locale/ko'
import {useStore} from "vuex";

dayjs().locale('ko')

const page = usePage();
const store = useStore()
const car = ref(page.props.car);
const isAuthenticated = page.props.auth.user !== null
const currentDay = ref(null)
const minimumEndDate = ref(null)
const reservationStartDate = ref(null);
const reservationEndDate = ref(null);
const reservationMessage = ref('');
const canMakeReservation = ref(false)
const totalPrice = ref(0)


onMounted(() => {
    currentDay.value = dayjs().format('YYYY-MM-DD')
    store.commit('SET_TEMPORARY_RESERVATION_ID', -1)
})


const onChangeStartDate = (e) => {
    if (reservationStartDate.value) {
        reservationStartDate.value = dayjs(e.target.value).format('YYYY-MM-DD')
        if (!reservationEndDate.value || dayjs(reservationStartDate.value).isAfter(dayjs(reservationEndDate.value)) || dayjs(reservationStartDate.value).isSame(dayjs(reservationEndDate.value))) {
            reservationEndDate.value = dayjs(e.target.value).add(1, 'day').format('YYYY-MM-DD');
        }
        const formStartDate = dayjs(reservationStartDate.value).format('YYYY-MM-DD HH:mm:ss')
        const formEndDate = dayjs(reservationEndDate.value).format('YYYY-MM-DD HH:mm:ss')

        if (store.state.temporaryReservationId === -1) {
            console.log('create')
            axios.post('/reservation/check', {
                start_date: formStartDate,
                end_date: formEndDate,
                user_id: page.props.auth.user.id,
                car_id: page.props.car.id
            }).then((res) => {
                reservationMessage.value = res.data.message
                console.log(res.data.reservationId)
                if (res.data.reservationId !== null) {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', res.data.reservationId)
                    canMakeReservation.value = true
                } else {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', -1);
                    canMakeReservation.value = false
                }

            }).catch((error) => {
                reservationMessage.value = "예약불가"
            })
        } else {
            console.log('update')
            axios.post('/reservation/update', {
                start_date: formStartDate,
                end_date: formEndDate,
                user_id: page.props.auth.user.id,
                car_id: page.props.car.id,
                reservation_id: store.state.temporaryReservationId
            }).then((res) => {
                reservationMessage.value = res.data.message
                console.log(res.data.reservationId)
                if (res.data.reservationId !== null) {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', res.data.reservationId)
                    canMakeReservation.value = true
                } else {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', -1);
                    canMakeReservation.value = false
                }

            }).catch((error) => {
                store.commit('SET_TEMPORARY_RESERVATION_ID', -1);
                reservationMessage.value = "예약불가"
            })
        }


    }
}

const onChangeEndDate = (e) => {
    reservationEndDate.value = dayjs(e.target.value).format('YYYY-MM-DD')

    if (reservationStartDate.value && reservationEndDate.value) {
        const formStartDate = dayjs(reservationStartDate.value).format('YYYY-MM-DD HH:mm:ss')
        const formEndDate = dayjs(reservationEndDate.value).format('YYYY-MM-DD HH:mm:ss')
        if (!reservationEndDate.value || dayjs(reservationStartDate.value).isAfter(dayjs(reservationEndDate.value)) || dayjs(reservationStartDate.value).isSame(dayjs(reservationEndDate.value))) {
            reservationStartDate.value = dayjs(e.target.value).subtract(1, 'day').format('YYYY-MM-DD');
        }
        if (store.state.temporaryReservationId === -1) {
            axios.post('/reservation/check', {
                start_date: formStartDate,
                end_date: formEndDate,
                user_id: page.props.auth.user.id,
                car_id: page.props.car.id
            }).then((res) => {
                reservationMessage.value = res.data.message
                console.log(res.data.reservationId)
                if (res.data.reservationId !== null) {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', res.data.reservationId)
                    canMakeReservation.value = res.data.message === "예약가능"
                } else {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', -1);
                    canMakeReservation.value = res.data.message === "예약불가"
                }

            }).catch((error) => {
                store.commit('SET_TEMPORARY_RESERVATION_ID', -1);
                reservationMessage.value = "예약불가"
            })
        } else {
            console.log('update')
            axios.post('/reservation/update', {
                start_date: formStartDate,
                end_date: formEndDate,
                user_id: page.props.auth.user.id,
                car_id: page.props.car.id,
                reservation_id: store.state.temporaryReservationId
            }).then((res) => {
                reservationMessage.value = res.data.message
                console.log(res.data.reservationId)
                if (res.data.reservationId !== null) {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', res.data.reservationId)
                    canMakeReservation.value = res.data.message === "예약가능"
                } else {
                    store.commit('SET_TEMPORARY_RESERVATION_ID', -1);
                    canMakeReservation.value = res.data.message === "예약불가"
                }


            }).catch((error) => {
                reservationMessage.value = "예약불가"
            })
        }
    }
}

const calculateTotalPrice = () => {
    const startDate = dayjs(reservationStartDate.value);
    const endDate = dayjs(reservationEndDate.value);

    if (startDate.isValid() && endDate.isValid() && startDate.isBefore(endDate)) {
        const days = endDate.diff(startDate, 'day'); // 일수 차이 계산
        const price = days * car.value.price;
        totalPrice.value = price
    } else {
        totalPrice.value = 0
    }
}


watchEffect(() => {
    calculateTotalPrice()
})


watch(reservationStartDate, (newValue, oldValue, onCleanup) => {
    minimumEndDate.value = dayjs(newValue).add(1, "day").format('YYYY-MM-DD')
})

</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : GuestLayout">
        <div class="p-8">
            <div class="bg-white p-8 rounded-md shadow-md max-w-screen-xl">
                <h2 class="text-2xl font-semibold mb-4">{{ car.company }} {{ car.model }} {{ car.year }}</h2>
                <img :src="car.imageUrl" alt="Car Image" class="w-full h-64 object-contain mb-4">

                <p class="text-gray-600">이동거리 : {{ car.mileage.toLocaleString('ko') }}km</p>
                <p class="text-gray-800 text-lg font-bold mb-4">일당 {{ car.price.toLocaleString('ko') }}원</p>


                <div class="mb-4">
                    <label for="reservationStartDate" class="block text-gray-600 text-sm font-medium mb-2">예약
                        날짜:</label>
                    <input v-model="reservationStartDate" @change="onChangeStartDate" type="date" :min="currentDay"
                           id="reservationStartDate" name="reservationStartDate" class="w-full p-2 border rounded-md">

                </div>

                <div v-if="reservationStartDate" class="mb-4">
                    <label for="reservationEndDate" class="block text-gray-600 text-sm font-medium mb-2">반환 날짜:</label>
                    <input v-model="reservationEndDate" @change="onChangeEndDate" type="date" :min="minimumEndDate"
                           id="reservationEndDate" name="reservationEndDate" class="w-full p-2 border rounded-md">
                </div>

                <div :class="{
                        'text-green-600': reservationMessage === '예약가능',
                        'text-red-600': reservationMessage === '예약불가',
                    }"
                     class="mb-4"
                >
                    {{ reservationMessage }}
                </div>

                <div class="mb-4">
                    총 가격: {{totalPrice.toLocaleString('ko')}} 원
                </div>

                <Link :href="route('reservation')">
                    <div class="flex items-center justify-end mt-6 ">
                        <button :class="{
                                'bg-blue-500 text-white p-2 rounded w-full': canMakeReservation,
                                'bg-gray-300 text-gray-500 p-2 rounded w-full cursor-not-allowed': !canMakeReservation
                            }"
                                :disabled="!canMakeReservation">예약하기
                        </button>
                    </div>
                </Link>

            </div>
        </div>
    </component>
</template>


<style scoped>
/* Add any additional styles here */
</style>
