<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

function conversation(id) {
  router.post(`/conversation/${id}`);
}

function makeFriend(id) {
  router.post(`/make-friend/${id}`);
}
</script>

<script>
import debounce from "lodash/debounce";
export default {
  props: {
    conversations: Object,
    users: Object,
    filters: Object,
    friends: Object,
    filtersFriend: Object,
  },
  data() {
    return {
      search: this.filters.search,
      searchFriend: this.filters.searchFriend,
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
    searchFriend: debounce(function (value) {
      this.$inertia.get(
        "/dashboard",
        { searchFriend: value },
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
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $page.props.auth.user.name }}
      </h2>
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
                placeholder="Search friends"
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
              <input
                v-model="searchFriend"
                type="text"
                placeholder="Search friends"
                class="w-full p-2 mb-4 mt-4 rounded-lg focus:outline-none"
              />
              <div class="grid grid-cols-6">
                <div v-for="friend in friends" :key="friend.id">
                  <div class="flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden">
                      <img
                        class="object-cover w-full h-full"
                        :src="friend.profileImage"
                        alt="Placeholder Image"
                      />
                    </div>
                    <p class="mt-2 text-lg font-medium">{{ friend.name }}</p>
                    <div class="flex items-center mt-2">
                      <button @click="makeFriend(friend.id)">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-6 w-6 mr-1"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
