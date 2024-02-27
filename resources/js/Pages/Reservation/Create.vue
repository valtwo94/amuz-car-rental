<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import dayjs from "dayjs";
import 'dayjs/locale/ko'
import {useStore} from "vuex";
import {onMounted, ref, watchEffect} from "vue";

dayjs().locale('ko')

const store = useStore()
const page = usePage()

const reservationForm = useForm({
    pickup_region: '',
    pay_method: '',
    pickup_time: '',
})

const canSubmit = ref(false)

const updateReservation =  () => {
    if (canSubmit) {
        const startDate = dayjs(page.props.reservation.start_date).format('YYYY-MM-DD')
        const pickupTime = startDate + " " + reservationForm.pickup_time

        axios.patch('/reservation', {
            reservation_id: page.props.reservation.id,
            pickup_region: reservationForm.pickup_region,
            pay_method: reservationForm.pay_method,
            pickup_time: pickupTime,
        })
            .then(response => {
                const message = response.data.message
                if(message==="예약성공"){
                    router.get(route('dashboard'))
                }else{
                    console.log(message)
                }
            })
            .catch(error => {
                // Handle error
                console.error(error);
            });
    }
}

watchEffect(() => {
    console.log(reservationForm.pickup_time)
    console.log(reservationForm.pay_method)
    console.log(reservationForm.pickup_region)
    if (reservationForm.pickup_region !== "" && reservationForm.pay_method !== '' && reservationForm.pickup_time !== '') {
        canSubmit.value = true
    }else{
        canSubmit.value = false
    }
})

onMounted(() => {
    if(!page.props.reservation){
        route('/')
    }
})

</script>

<template>
    <Head>
        <title>예약생성</title>
    </Head>
    <AuthenticatedLayout>
        <div>
            <h2 class="text-2xl font-bold mb-4">추가 정보 입력</h2>

            <!-- Display existing reservation data -->
            <div class="mb-4">
                <p class="mb-2">차량명: {{ page.props.reservation.car.company }} {{ page.props.reservation.car.model }}
                    {{ page.props.reservation.car.year }}</p>
                <p class="mb-2">대여 날짜: {{ dayjs(page.props.reservation.start_date).format("YYYY년 MM월 DD일") }}</p>
                <p class="mb-2">반환 날짜: {{ dayjs(page.props.reservation.end_date).format('YYYY년 MM월 DD일') }}</p>
                <p class="mb-2">총 가격: {{ page.props.reservation.total_price.toLocaleString('ko') }}원</p>
            </div>

            <!-- Add form to edit reservation data -->
            <form @submit.prevent="updateReservation" class="mb-4">
                <!-- Add input fields for new fields with Tailwind styling -->
                <div class="mb-4">
                    <label for="pickup_region" class="block text-sm font-medium text-gray-700">픽업 지역</label>
                    <input v-model="reservationForm.pickup_region" id="pickup_region" name="pickup_region" type="text"
                           class="mt-1 p-2 w-full border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="pay_method" class="block text-sm font-medium text-gray-700">지불수단</label>
                    <select v-model="reservationForm.pay_method" id="pay_method" name="pay_method"
                            class="mt-1 p-2 w-full border rounded-md">
                        <option value="card">카드</option>
                        <option value="cash">현금</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="pickup_time" class="block text-sm font-medium text-gray-700">픽업 시간</label>
                    <input v-model="reservationForm.pickup_time" id="pickup_time" name="pickup_time" type="time"
                           class="mt-1 p-2 w-full border rounded-md">
                </div>

                <button type="submit"
                        :class="{ 'bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600': canSubmit, 'bg-gray-300 text-gray-500 px-4 py-2 rounded-md cursor-not-allowed': !canSubmit }"
                        :disabled="!canSubmit"
                >
                    예약 확정
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

