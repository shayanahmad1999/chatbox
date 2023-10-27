<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from "vue";

const form = useForm({
  image: "",
});

let submit = () => {
    form.post(route('profile.image'));
    form.reset('image')
}

const imageUrl = ref(null);

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    imageUrl.value = URL.createObjectURL(file);
    form.image = file;
  }
};
const displayErrors = computed(() => {
    if (form.errors.image) {
        return { image: form.errors.image[0] };
    }
    return {};
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Image Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your image information .
            </p>
        </header>

        <form @submit.prevent="submit" enctype="multipart/form-data" class="mt-6 space-y-6">
            <div>
                <img v-if="imageUrl" :src="imageUrl" alt="Selected Image" />
                <InputError class="mt-2" :message="displayErrors.image" />
                <InputLabel for="image" value="Image" />
                <!-- @input="form.image = $event.target.files[0]" -->
                <TextInput
                    id="image"
                    type="file"
                    class="mt-1 block w-full"
                    name="image"
                    @change="handleImageUpload"
                    required
                    autofocus
                    autocomplete="image"
                />
                
                <InputError class="mt-2" :message="form.errors.image" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
