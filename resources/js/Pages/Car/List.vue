<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import CarList from "@/Components/Car/CarList.vue";
import {usePage} from '@inertiajs/vue3';
import {onMounted} from "vue";

const page = usePage();
const isAuthenticated = page.props.auth.user !== null;

defineProps({
    cars: Array
})

</script>

<template>
    <component :is="isAuthenticated ? AuthenticatedLayout : GuestLayout">
        <CarList :cars="$page.props.cars"/>

    </component>

</template>

<style scoped>
/* 필요한 경우 스타일링 추가 */
</style>
