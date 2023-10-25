<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

let props = defineProps({
  conversations: Object,
  user: Object,
  messages: Object,
});

let form = useForm({
  conversationId: props.conversations.id,
  content: "",
});

let submit = () => {
  form.post("/chat/create");
  form.reset("content");
};

function messageDelete(message){
  router.delete('/message/delete/'+message);
}

</script>
<script>
export default {
  methods: {
    formatDate(date) {
      const options = {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      };
      return new Date(date).toLocaleString("en-US", options);
    },
  },
};
</script>
<template>
  <Head title="Chat" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chatbox</h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex ">
            <div class="w-1/4 bg-gray-200 p-4"></div>

            <div class="w-3/4 p-4">
              <div class="flex flex-col h-full bg-white rounded-lg">
                <div class="flex-grow border-b">
                  <div class="flex flex-col h-full overflow-y-auto">
                    <div class="flex items-start">
                      <div
                        class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-300"
                      ></div>

                      <div class="ml-3 mt-2">
                        <h2 class="text-lg font-semibold mb-4">
                          {{ user.name }}
                        </h2>
                      </div>
                    </div>
                    <div
                      v-for="(message, index) in messages"
                      :key="index"
                      class="my-4 mx-6"
                    >
                      <div
                        v-if="
                          message.sender.name === $page.props.auth.user.name
                        "
                        class="flex items-start"
                      >
                        <div
                          class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-300"
                        ></div>
                        <div class="ml-3">
                          <p class="text-sm font-medium">
                            {{ message.sender.name }}
                          </p>
                          <div class="bg-gray-100 rounded-lg p-2 mt-1">
                            <button @click="messageDelete(message.id)">{{ message.content }}</button>
                            <span class="ml-5">{{
                              formatDate(message.created_at)
                            }}</span>
                          </div>
                        </div>
                      </div>

                      <div
                        v-else
                        class="flex items-end justify-end"
                      >
                        <div class="mr-3">
                          <p class="text-sm font-medium">
                            {{ message.sender.name }}
                          </p>
                          <div class="bg-gray-100 rounded-lg p-2 mt-1">
                            {{ message.content }}
                            <span class="mr-5">{{
                              formatDate(message.created_at)
                            }}</span>
                          </div>
                        </div>
                        <div
                          class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-300"
                        ></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex-none">
                  <input
                    type="text"
                    placeholder="Type your message here"
                    class="w-full p-4 focus:outline-none"
                    v-model="form.content"
                    @keydown.enter.prevent="submit"
                  />
                </div>
                <InputError :message="form.errors.content" class="mt-2" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>