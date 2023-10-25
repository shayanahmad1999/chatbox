<script setup>
import { ref } from 'vue';
const users = ref([
  { name: "User1" },
  { name: "User2" },
  // Add other users here
]);

const messages = ref([
  { sender: "User1", text: "Hello there!" },
  { sender: "User2", text: "Hi! How are you?" },
  // Add other messages here
]);
</script>
<template>
     <div class="flex h-screen">
                        <div class="w-1/4 bg-gray-200 p-4">
                            <h2 class="text-lg font-semibold mb-4">Users</h2>
                            <input v-model="searchQuery" type="text" placeholder="Search users" class="w-full p-2 mb-4 rounded-lg focus:outline-none" />
                            <ul>
                                <li v-for="(user, index) in users" :key="index" class="mb-2 px-4 py-2 hover:bg-gray-300 cursor-pointer">{{ user.name }}</li>
                            </ul>
                        </div>
                        <div class="w-3/4 p-4">
                            <div class="flex flex-col h-full bg-white rounded-lg">
                                <div class="flex-grow border-b">
                                    <div class="flex flex-col h-full overflow-y-auto">
                                        <div v-for="(message, index) in messages" :key="index" class="my-4 mx-6">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-300"></div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium">{{ message.sender }}</p>
                                                    <div class="bg-gray-100 rounded-lg p-2 mt-1">{{ message.text }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-none">
                                    <input type="text" placeholder="Type your message here" class="w-full p-4 focus:outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>
</template>