<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

function conversation(id) {
  router.post(`/conversation/${id}`);
}


const messages = ref([
  { sender: "User1", text: "Hello there!" },
  { sender: "User2", text: "Hi! How are you?" },
]);
</script>

<script>
import debounce from "lodash/debounce";
export default {
  props: {
    conversations: Object,
    users: Object,
    filters: Object,
  },
  data() {
    return {
      search: this.filters.search,
    };
  },

  watch: {
    search: debounce(function (value) {
      this.$inertia.get(
        "/dashboard",
        { search: value },
        {
          preserveState: true,
          replace: true,
        }
      );
    }, 300),
  },
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chatbox</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex h-screen">
            <div class="w-1/4 bg-gray-200 p-4">
              <h2 class="text-lg font-semibold mb-4">Users</h2>
              <input
                v-model="search"
                type="text"
                placeholder="Search users"
                class="w-full p-2 mb-4 rounded-lg focus:outline-none"
              />
              <ul>
                <li
                  v-for="user in users.data"
                  :key="user.id"
                  class="mb-2 px-4 py-2 hover:bg-gray-300 cursor-pointer"
                >
                  <button @click="conversation(user.id)">
                    {{ user.name }}
                  </button>
                </li>
              </ul>
            </div>
            <div class="w-3/4 p-4">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
