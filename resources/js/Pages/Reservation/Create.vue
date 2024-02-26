<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import {onMounted, reactive, ref, watchEffect} from "vue";
import dayjs from "dayjs";
import 'dayjs/locale/ko'
import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';

dayjs().locale('ko')


const page = usePage();

const selectedCar = ref(null)
const reservationAvailableMessage = ref('')

const errorMessages = reactive({
    startTime: '',
    endTime: '',
});

const timeSelection = reactive({
    start_date: '',
    start_time: '',
    end_date: '',
    end_time: '',
})

const reservationForm = useForm({
    start_date: '',
    end_date: '',
    car_id: -1,
    user_id: page.props.auth.user.id,
    total_price: 0
});

onMounted(() => {
    const currentDateTime = dayjs().format('YYYY-MM-DD HH:mm')
    const currentDate = dayjs().format('YYYY-MM-DD')
    const currentTime = dayjs().format('HH:00')
    timeSelection.start_date = currentDate
    timeSelection.end_date = currentDate
    timeSelection.start_time = currentTime
    timeSelection.end_time = currentTime
    reservationForm.start_date = currentDateTime
    reservationForm.end_date = currentDateTime
    flatpickr("#start_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        local: 'ko',
        minuteIncrement: 60,
        onChange: (selectedDates, dateStr, instance) => {
            onChangeStartTime(dateStr);
        }
    });
    flatpickr("#end_time", {
        enableTime: true,
        noCalendar: true,
        local: 'ko',
        dateFormat: "H:i",
        minuteIncrement: 60,
        onChange: (selectedDates, dateStr, instance) => {
            onChangeEndTime(dateStr);
        }
    });
})


const onChangeStartDate = (e) => {
    const newDate = e.target.value
    const startDateTime = dayjs(newDate + ' ' + timeSelection.start_time);
    const endDateTime = dayjs(timeSelection.end_date + ' ' + timeSelection.end_time);
    reservationForm.start_date = startDateTime.format('YYYY-MM-DD HH:mm:ss');

    if (!startDateTime.isBefore(endDateTime)) {
        timeSelection.start_time = dayjs(reservationForm.start_date).format('YYYY-MM-DD')
        timeSelection.start_time = dayjs(reservationForm.start_date).format('HH:mm')
    }

}
const onChangeStartTime = (dateStr) => {
    const startDateTime = dayjs(timeSelection.start_date + ' ' + dateStr);
    const endDateTime = dayjs(timeSelection.end_date + ' ' + timeSelection.end_time);
    reservationForm.start_date = startDateTime.format('YYYY-MM-DD HH:mm:ss');

    if (startDateTime.isBefore(endDateTime)) {
        errorMessages.startTime = ''
        errorMessages.endTime = ''
    } else {
        errorMessages.startTime = '시작시간은 반드시 종료시간 이전으로 되있어야합니다.'
    }
};
const onChangeEndTime = (dateStr) => {
    const newDateTime = dayjs(timeSelection.end_date + ' ' + dateStr);
    const startDateTime = dayjs(timeSelection.start_date + ' ' + timeSelection.start_time);
    reservationForm.end_date = newDateTime.format('YYYY-MM-DD HH:mm:ss');

    if (newDateTime.isAfter(startDateTime)) {
        errorMessages.endTime = ''
        errorMessages.startTime = ''
    } else {
        errorMessages.endTime = '종료시간은 반드시 시작시간 이후로 되있어야합니다.'
    }
}
const calculatePrice = () => {
    if (selectedCar.value && reservationForm.start_date && reservationForm.end_date) {
        const startDateTime = dayjs(reservationForm.start_date);
        const endDateTime = dayjs(reservationForm.end_date);
        const durationInHours = endDateTime.diff(startDateTime, 'hours');
        const hourlyPrice = selectedCar.value.price / 24;
        reservationForm.total_price = durationInHours * hourlyPrice;
    }

    return 0
}

watchEffect(() => {
    calculatePrice()
})
const createReservation = () => {

};
</script>

<template>
    <Head>
        <title>예약생성</title>
    </Head>
    <AuthenticatedLayout>
        <h2 class="text-2xl font-bold mb-4">추가 정보 입력</h2>


    </AuthenticatedLayout>
</template>

