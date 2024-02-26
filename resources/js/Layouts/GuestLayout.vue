<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link, usePage} from '@inertiajs/vue3';

const page = usePage()
const authCheck = page.props.auth.check
</script>

<template>


    <div class="min-h-screen flex flex-col sm:justify-start items-center pt-6 sm:pt-0 bg-gray-100 ">
        <!-- 헤더 -->
        <header class="w-full bg-white shadow-md mb-4 fixed top-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- 로고 및 홈 링크 -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <Link href="/">
                                <ApplicationLogo class="w-8 h-8 fill-current text-gray-500"/>
                            </Link>
                        </div>

                    </div>

                    <div class="flex items-center">
                        <Link v-if="!authCheck && !page.url.includes('/login')" href="/login"
                              class="text-gray-700 hover:text-gray-900">로그인
                        </Link>
                        <Link v-if="!authCheck && !page.url.includes('/register')" href="/register"
                              class="ml-4 text-gray-700 hover:text-gray-900">회원가입
                        </Link>
                    </div>
                </div>
            </div>
        </header>


        <!-- 콘텐츠 -->
        <main class="p-8 mt-20">
            <slot/>
        </main>


    </div>
</template>
