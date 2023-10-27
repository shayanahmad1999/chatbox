<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
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
  image: "",
});

let submit = () => {
  form.post("/chat/create");
  form.reset("content");
};

let insert = () => {
  form.post("/chat/create/image");
  form.reset("image");
};

const imageUrl = ref(null);

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    imageUrl.value = URL.createObjectURL(file);
    form.image = file;
  }
};

function messageDelete(message) {
  router.delete("/message/delete/" + message);
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
    shouldShowImage(message) {
      console.log('Upload Image:', message.uploadImage);
      if (message.uploadImage) {
        const extension = message.uploadImage.split('.').pop();
        const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Add more if needed
        return imageExtensions.includes(extension.toLowerCase());
      }
      return false;
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
          <div class="flex">
            <div class="w-1/4 bg-gray-200 p-4"></div>

            <div class="w-3/4 p-4">
              <div class="flex flex-col h-full bg-white rounded-lg">
                <div class="flex-grow border-b mb-5">
                  <div class="flex flex-col h-full overflow-y-auto">
                    <div class="flex items-start">
                      <div
                        class="flex-shrink-0 w-20 h-10 mb-10 rounded-full bg-gray-300"
                      >
                        <img :src="user.profileImage" alt="no-image" />
                      </div>

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
                        v-if="message.name === $page.props.auth.user.name"
                        class="flex items-start"
                      >
                        <div
                          class="flex-shrink-0 w-20 h-10 rounded-full bg-gray-300"
                        >
                          <img :src="message.userImage" alt="no-image" />
                        </div>
                        <div class="ml-3">
                          <p class="text-sm font-medium">
                            {{ message.name }}
                          </p>
                          <div class="bg-gray-100 rounded-lg p-2 mt-1">
                            <span>
                              <button @click="messageDelete(message.id)">
                                <span v-if="message.content">{{
                                  message.content
                                }}</span>
                                  <img
                                  v-if="shouldShowImage(message)"
                                  :src="`${message.uploadImage}`"
                                />
                              </button>
                            </span>

                            <span class="ml-5"
                              >{{ formatDate(message.created_at) }}
                            </span>
                          </div>
                        </div>
                      </div>

                      <div v-else class="flex items-end justify-end">
                        <div class="mr-3">
                          <p class="text-sm font-medium">
                            {{ message.name }}
                          </p>
                          <div class="bg-gray-100 rounded-lg p-2 mt-1">
                            <span>
                              <button @click="messageDelete(message.id)">
                                <span v-if="message.content">{{
                                  message.content
                                }}</span>
                                  <img
                                  v-if="shouldShowImage(message)"
                                  :src="message.uploadImage"
                                />
                              </button>
                            </span>
                            <span class="ml-5">{{
                              formatDate(message.created_at)
                            }}</span>
                          </div>
                        </div>
                        <div
                          class="flex-shrink-0 w-20 h-10 rounded-full bg-gray-300"
                        >
                          <img :src="message.userImage" alt="no-image" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex-none grid grid-cols-4">
                  <input
                    type="text"
                    placeholder="Type your message here"
                    class="p-4 mt-2 w-full focus:outline-none"
                    v-model="form.content"
                    @keydown.enter.prevent="submit"
                  />
                  <TextInput
                    id="image"
                    type="file"
                    class="p-4 mt-2"
                    name="image"
                    @change="handleImageUpload"
                    required
                    autofocus
                    autocomplete="image"
                  />
                  <button
                    @click="insert"
                    class="p-4 border text-xl text-gray-900 hover:text-indigo-900 border-gray-900 hover:border-indigo-700 mt-2"
                  >
                    upload
                  </button>
                  <img
                    class="mt-5 ml-40"
                    v-if="imageUrl"
                    width="50"
                    :src="imageUrl"
                    alt="Selected Image"
                  />
                </div>
                <InputError :message="form.errors.content" class="mt-2" />
                <InputError class="mt-2" :message="form.errors.image" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>