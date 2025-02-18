<template>
  <div v-if="jobs" class="mx-8">
    <ul role="list" class="divide-y divide-gray-100">
      <li v-for="job in jobs" :key="job.job_id">
        <a :href="`/jobs/${job.job_id}`">
          <div class="flex justify-between gap-x-6 py-5 hover:bg-gray-50">
            <div class="flex min-w-0 gap-x-4">
              <BriefcaseIcon class="h-6" />
              <div class="min-w-0 flex-auto">
                <p class="text-sm/6 font-semibold text-gray-900">{{ job.title }}</p>
                <p class="mt-1 truncate text-xs/5 text-gray-500">{{ job.title }}</p>
              </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
              <p class="text-sm/6 text-gray-900">{{ job.title }}</p>
            </div>
          </div>
        </a>
      </li>
    </ul>
  </div>

  <div v-if="meta" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
      <a href="#" @click.prevent="fetchJobs(meta.current_page - 1)" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
      <a href="#" @click.prevent="fetchJobs(meta.current_page + 1)" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          {{ ' ' }}
          <span class="font-medium">{{ meta.entries_from }}</span>
          {{ ' ' }}
          to
          {{ ' ' }}
          <span class="font-medium">{{ Math.min(meta.entries_to, meta.entries_total) }}</span>
          {{ ' ' }}
          of
          {{ ' ' }}
          <span class="font-medium">{{ meta.entries_total }}</span>
          {{ ' ' }}
          results
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
          <a v-for="page in totalPages" :key="page" href="#" @click.prevent="fetchJobs(page)" :class="{'z-10 bg-indigo-600 text-white': page === currentPage, 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50': page !== currentPage}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 focus:outline-offset-0">{{ page }}</a>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { BriefcaseIcon } from '@heroicons/vue/24/outline';
import {computed} from "vue";
import {JobsApiResponse, Job, Meta} from "@/types";

const jobs = ref<Job[]>();
const meta = ref<Meta>();
const currentPage = ref<number>();

const fetchJobs = async (page: number) => {
  const response: AxiosResponse = await axios.get(`/api/jobs?page=${page}`);
  const data: JobsApiResponse = JSON.parse(response.data);

  jobs.value = data.payload;
  meta.value = data.meta;
  currentPage.value = page;
};


const totalPages = computed(() => {
  const entriesPerPage = 10;
  return meta.value ? Math.ceil(meta.value.entries_total / entriesPerPage) : 0;
});

onMounted(() => {
  const page = 1
  fetchJobs(page);
});

</script>