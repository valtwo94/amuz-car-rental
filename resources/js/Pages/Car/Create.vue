<script setup>
import {ref, watchEffect} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const page = usePage();

const form = useForm({
    model: '',
    year: '',
    price: 0,
    company: '',
    mileage: 0,
    imageUrl: '',
    status: 'available',
});

const canSubmit = ref(false)

const submitForm = () => {
    form.post(route('cars.store'), {
        onSuccess: () => {
            form.reset()
        },
        onFinish: () => {

        }
    });
};

watchEffect(() => {
    if(form.model.trim() !== "" && form.year.trim() !== ""&& form.price !== 0 && form.company.trim() !== "" && form.company.trim() !== ''&& form.mileage !== 0 && form.imageUrl.trim() !== "" ){
        canSubmit.value = true
    }else{
        canSubmit.value = false
    }
})


</script>

<template>
    <AuthenticatedLayout>
        <div class="flex flex-row justify-between">
            <h1 class="text-2xl font-bold mb-6">차량 생성</h1>

        </div>


        <form @submit.prevent="submitForm" class="max-w-full bg-white p-6 rounded-md shadow-md">
            <div class="mb-4">
                <label for="model" class="block text-gray-600 text-sm font-medium mb-2">모델:</label>
                <input v-model="form.model" type="text" id="model" name="model"
                       class="w-full p-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="year" class="block text-gray-600 text-sm font-medium mb-2">연식:</label>
                <input v-model="form.year" type="text" id="year" name="year" class="w-full p-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-600 text-sm font-medium mb-2">가격(일당):</label>
                <input v-model="form.price" type="number" id="price" name="price"
                       class="w-full p-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="company" class="block text-gray-600 text-sm font-medium mb-2">회사:</label>
                <input v-model="form.company" type="text" id="company" name="company"
                       class="w-full p-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="mileage" class="block text-gray-600 text-sm font-medium mb-2">이동거리:</label>
                <input v-model="form.mileage" type="number" id="mileage" name="mileage"
                       class="w-full p-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="imageUrl" class="block text-gray-600 text-sm font-medium mb-2">이미지 URL:</label>
                <input v-model="form.imageUrl" type="text" id="imageUrl" name="imageUrl"
                       class="w-full p-2 border rounded-md">
            </div>


            <div class="mb-4">
                <label for="status" class="block text-gray-600 text-sm font-medium mb-2">상태:</label>
                <select v-model="form.status" id="status" name="status" class="w-full p-2 border rounded-md">
                    <option value="available">사용 가능</option>
                    <option value="unavailable">사용 불가능</option>
                    <option value="maintenance">보수 중</option>
                </select>
            </div>


            <div class="flex items-center justify-end mt-6">
                <button type="submit" :class="{ 'bg-blue-500 text-white p-2 rounded w-full cursor-pointer hover:bg-blue-600': canSubmit, 'bg-gray-300 text-gray-500 p-2 rounded w-full cursor-not-allowed': !canSubmit }" :disabled="!canSubmit">
                    추가하기
                </button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
