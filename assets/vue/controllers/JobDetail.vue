<template>
  <div v-if="job" class="mx-8 my-4">
    <div class="px-4 sm:px-0" >
      <h3 class="text-base/7 font-semibold text-gray-900">{{ job.title }}</h3>
      <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">{{ job.locations.join(' | ') }}</p>
    </div>
    <div class="mt-6 border-t border-gray-100">
      <dl class="divide-y divide-gray-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm/6 font-medium text-gray-900">About</dt>
          <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0" v-html="job.description"></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm/6 font-medium text-gray-900">Salary expectation</dt>
          <dd v-if="job.salaryMin && job.salaryMax" class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ job.salaryMin }} - {{ job.salaryMax}}</dd>
          <dd v-else-if="job.salaryMin" class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">From {{ job.salaryMin }}</dd>
          <dd v-else-if="job.salaryMax" class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">Upto {{ job.salaryMax }}</dd>
        </div>
      </dl>
    </div>
  </div>

  <ApplyForJobForm :job="job" v-if="job"/>
</template>

<script setup lang="ts">
import axios, {AxiosResponse} from "axios";
import {Job} from "@/types";
import {onMounted, ref} from "vue";
import ApplyForJobForm from "./../components/ApplyForJobForm.vue";

const props = defineProps<{ id: number }>();

const job = ref<Job|null>();

const fetchJob = async () => {
  const response: AxiosResponse = await axios.get(`/api/jobs/${props.id}`);
  job.value = response.data;
};

onMounted(() => {
  fetchJob();
});
</script>