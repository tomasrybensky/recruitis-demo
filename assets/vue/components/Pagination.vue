<template>
  <div
    v-if="props.meta"
    class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
  >
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          {{ translator.trans("pagination.showing") }}
          {{ " " }}
          <span class="font-medium">{{ props.meta.entriesFrom }}</span>
          {{ " " }}
          {{ translator.trans("pagination.to") }}
          {{ " " }}
          <span class="font-medium">{{
            Math.min(props.meta.entriesTo, props.meta.entriesTotal)
          }}</span>
          {{ " " }}
          {{ translator.trans("pagination.of") }}
          {{ " " }}
          <span class="font-medium">{{ props.meta.entriesTotal }}</span>
          {{ " " }}
          {{ translator.trans("pagination.results") }}
        </p>
      </div>
      <div>
        <nav
          class="isolate inline-flex -space-x-px rounded-md shadow-xs"
          aria-label="Pagination"
        >
          <a
            v-for="page in totalPages"
            :key="page"
            href="#"
            @click.prevent="callChangedPageAction(page)"
            :class="{
              'z-10 bg-indigo-600 text-white': page === currentPage,
              'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50':
                page !== currentPage,
            }"
            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 focus:outline-offset-0"
            >{{ page }}</a
          >
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Meta } from "@/types";
import { computed, ref } from "vue";
import { BazingaTranslator } from "bazinga-translator";

const props = defineProps<{
  pageChangedAction: (page: number) => Promise<void>;
  meta: Meta;
}>();

const callChangedPageAction = async (page: number) => {
  currentPage.value = page;
  await props.pageChangedAction(page);
};

const currentPage = ref<number>(1);
declare const Translator: BazingaTranslator;
const translator: BazingaTranslator = Translator;

const totalPages = computed(() => {
  const entriesPerPage: number = 10; //fixed in this demo
  return props.meta ? Math.ceil(props.meta.entriesTotal / entriesPerPage) : 0;
});
</script>
