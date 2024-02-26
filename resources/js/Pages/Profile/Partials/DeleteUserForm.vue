<script setup>
import DangerButton from '@/Components/Car/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/Car/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">계정</h2>

            <p class="mt-1 text-sm text-gray-600">
                "계정이 삭제되면 모든 자원과 데이터가 영구적으로 삭제됩니다. 계정을 삭제하기 전에 보존하고 싶은 모든 데이터나 정보를 다운로드해 주시기 바랍니다."
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">계정 삭제</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    정말 계정을 삭제하시겠습니까?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    "계정이 삭제되면 모든 자원과 데이터가 영구적으로 삭제됩니다. 계정을 삭제하기 전에 보존하고 싶은 모든 데이터나 정보를 다운로드해 주시기 바랍니다."
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> 취소 </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        계정 삭제
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
