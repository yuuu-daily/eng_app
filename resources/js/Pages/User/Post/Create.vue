<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Highlight from '@tiptap/extension-highlight'
import TextAlign from '@tiptap/extension-text-align'
import AppLayout from '@/Layouts/User/AppLayout.vue'
import {router, useForm, usePage} from '@inertiajs/vue3';
import InputLabel from "@/Components/InputLabel.vue";
import MyButtonGoBack from "@/Components/Commons/MyButtonGoBack.vue";
import PrimaryButton from "@/Components/Commons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PickUpCategories from "@/Pages/User/Post/Partials/PickUpCategories.vue";

const props = defineProps({
    photo_url: null,
    short_url: null,
    categories: null,
});

const auth = usePage().props.auth.user;
const editor = ref(null);
const showMenu = ref(false);
let selected_categories = [];

const form = useForm({
    title: null,
    content: null,
    category_ids: [],
    photo_url: null,
    photo_file: null,
    category_changed: false
});

onMounted(() => {
  editor.value = new Editor({
    extensions: [
      StarterKit,
      Highlight,
      TextAlign.configure({
        types: ['heading', 'paragraph'],
      }),
    ],
    content: form.content,
    editorProps: {
      attributes: {
        class: 'tiptap',
      },
    },
    onUpdate: ({ editor }) => {
      form.content = editor.getHTML();
    },
  })
});

function submit() {
    if (form.photo_file) {
        uploadFile(form.photo_file);
    } else {
        upd();
    }
}

// function uploadFile(file) {
//     let params = new URLSearchParams();
//     const ts = dayjs().format('YYMMDDHHmmss');
//     params.append('filename', 'j' + ts + '.' + file.name.split('.').pop()); //キャッシュ回避のため
//     params.append('dir', 'post/' + 1 + '/upload/');
//     params.append('type', file.type);
//     axios.post('/util/get_presignedurl', params).then(function (res1) {
//         const axiosOptions = {headers: {'Content-Type': file.type}};
//         if (res1.status === 200) {
//             axios.put(res1.data.preSignedUrl, file, axiosOptions).then(function (res2) {
//                 form.photo_file = null; //用済み(これでいいか微妙)
//                 // form.photo_url = res1.data.s3key; //更新するCDNのURL
//                 form.photo_url = res1.data.preSignedUrl; //更新するCDNのURL
//                 // console.log(res1.data.preSignedUrl);
//                 upd();
//             }).catch(function (e) {
//                 toaster.error('保存に失敗しました(2)');
//                 console.log(e);
//             });
//         }
//     }).catch(function (e) {
//         toaster.error('保存に失敗しました(3)');
//         console.log(e);
//     });
// }

const upd = () => {
    form.post(route('user.post.store'), {
        onSuccess: () => {
            toaster.success('保存しました');
            router.visit('/post' );
        },
        onError: () => {
            toaster.error('保存に失敗しました');
        },
    });
};

onBeforeUnmount(() => {
  editor.value?.destroy()
})

// ブロックタイプ設定用関数
function setBlock(type, options = {}) {
  const chain = editor.value?.chain().focus()
  if (!chain) return

  switch (type) {
    case 'horizontalRule':
      chain.setHorizontalRule().run()
      break
    case 'orderedList':
      chain.toggleOrderedList().run()
      break
    case 'codeBlock':
      chain.toggleCodeBlock().run()
      break
    case 'blockquote':
      chain.toggleBlockquote().run()
      break
    case 'heading':
      chain.toggleHeading(options).run()
      break
    case 'highlight':
      chain.toggleHighlight().run()
      break
    case 'strike':
      chain.toggleStrike().run()
      break
  }

  showMenu.value = false
}

function openFile(url) {
    window.open(url, '_blank');
}

const toolbarItems = [
  { type: 'heading', label: 'H1', isActive: () => editor.value?.isActive('heading', { level: 1 }), options: { level: 1 } },
  { type: 'heading', label: 'H2', isActive: () => editor.value?.isActive('heading', { level: 2 }), options: { level: 2 } },
  { type: 'highlight', label: 'Highlight', isActive: () => editor.value?.isActive('highlight') },
  { type: 'orderedList', label: 'Ordered List', isActive: () => editor.value?.isActive('orderedList') },
  { type: 'codeBlock', label: 'Code Block', isActive: () => editor.value?.isActive('codeBlock') },
  { type: 'strike', label: '取り消し線', isActive: () => editor.value?.isActive('strike') },
  { type: 'blockquote', label: 'Blockquote', isActive: () => editor.value?.isActive('blockquote') },
  // Dividerはアクティブ状態が存在しないので別で扱う（後述）
]


function updateSelectedCategories(val) {
    form.category_ids = val; //formのデータを更新
    form.category_changed = true; //少しでもいじったらフラグをオン！
}

</script>

<template>
    <AppLayout :photo_url="props.photo_url">
      <div class="my-panel">
        <div class="mb-4 px-4 w-full sm:w-1/2 col-span-6 sm:col-span-4">
          <InputLabel for="course_name" value="タイトル"/>
          <TextInput
              id="title"
              v-model="form.title"
              type="text"
              class="mt-1 block w-full"
          />
          <InputError class="mt-2" :message="form.errors.title"/>
        </div>
        <div class="mb-4 px-4">
            <InputLabel for="category" value="カテゴリ"/>
            <PickUpCategories :categories="categories" :category-ids="selected_categories" @update="updateSelectedCategories"/>
            <InputError class="mt-2" :message="form.errors.categoroes"/>
        </div>
      <div v-if="editor" class="container p-4">
      <!-- Toolbar -->
      <div class="flex gap-2 mb-4">
        <button @click="showMenu = !showMenu" class="px-2 py-1 border rounded hover:bg-gray-100">
          ＋
        </button>

        <!-- ポップアップメニュー -->
        <div v-if="showMenu" class="absolute z-10 mt-10 w-48 bg-white border rounded shadow-lg">
          <ul class="text-sm">
  <li v-for="item in toolbarItems" :key="item.label">
    <button
      @click="setBlock(item.type, item.options || {})"
      :class="[
        'block w-full text-left px-4 py-2 text-sm',
        item.isActive()
          ? 'bg-gray-800 text-white border-gray-800'
          : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
      ]"
    >
      {{ item.label }}
    </button>
  </li>

  <!-- Dividerだけ別で追加（常に非アクティブなので） -->
  <li>
    <button
      @click="setBlock('horizontalRule')"
      class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
    >
      Divider
    </button>
  </li>
</ul>

        </div>
      </div>

      <!-- エディタ -->
      <InputLabel for="course_name" value="記事投稿" class="my-2"/>
      <editor-content :editor="editor" @click="editor?.commands.focus()" class="tiptap px-2 w-full rounded border min-h-[15rem] bg-white"/>
    </div>
    <!-- <div class="mr-4 flex-shrink-0 mb-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <MyImageUploader :maxSize="8000" title="写真" :imgUrl="form.photo_url" v-model:imgFile="form.photo_file"/>
            </div>
            <div>
                <InputLabel for="name" value="写真(申請)"/>

                <img :src="form.photo_url_tmp">
            </div>
            <div v-if="props.photo_url" class="py-2 px-6 w-full link-primary">
                    <span
                        @click="openFile(props.photo_url)"
                        class="cursor-pointer text-blue-600 hover:underline"
                    >{{ props.short_url }}</span>
            </div>
        </div>
    </div> -->
      <div>
          <PrimaryButton class="mt-3 me-3" :class="{ 'opacity-25': form.processing }"
                          :disabled="form.processing" @click="submit()">
              保存
          </PrimaryButton>
          <MyButtonGoBack :target="route('user.post.index')"/>
      </div>
    </div>
  </AppLayout>
</template>


<style scoped>
/* 見出し */
::v-deep(.tiptap h1) {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 1.25rem 0 0.75rem;
  line-height: 1.3;
}

::v-deep(.tiptap h2) {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 1rem 0 0.5rem;
  line-height: 1.35;
}

/* ::v-deep(.tiptap h3) {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0.75rem 0 0.5rem;
} */

/* 段落 */
::v-deep(.tiptap p) {
  margin: 0.5rem 0;
  line-height: 1.3;
}

/* リスト */
::v-deep(.tiptap ul),
::v-deep(.tiptap ol) {
  margin: 1rem 0;
  padding-left: 1.25rem;
  line-height: 1.6;
}

::v-deep(.tiptap li) {
  margin-bottom: 0.25rem;
  list-style-type: disc;
}

::v-deep(.tiptap ol) li {
  list-style-type: decimal;
}

/* blockquote */
::v-deep(.tiptap blockquote) {
  quotes: none;
  border-left: 4px solid #cbd5e1;
  padding-left: 1rem;
  margin: 1.5rem 0;
  padding: 0.5rem 1rem;
  color: #475569;
  font-style: italic;
  background-color: #f8fafc;
}

::v-deep(.tiptap blockquote::before),
::v-deep(.tiptap blockquote::after) {
  content: none;
}

/* code */
::v-deep(.tiptap code) {
  background-color: #f3e8ff;
  border-radius: 0.4rem;
  color: #1f1f1f;
  font-size: 0.85rem;
  padding: 0.25em 0.3em;
}

/* code block */
::v-deep(.tiptap pre) {
  background-color: #1e293b;
  color: #f8fafc;
  border-radius: 0.5rem;
  font-family: 'JetBrainsMono', monospace;
  font-size: 0.9rem;
  line-height: 1.5;
  padding: 1rem;
  margin: 1.5rem 0;
  overflow-x: auto;
  white-space: pre-wrap;
  tab-size: 2;
}

::v-deep(.tiptap pre code) {
  background: none;
  color: inherit;
  padding: 0;
  font-size: inherit;
}

::v-deep(.tiptap:focus),
::v-deep(.tiptap[contenteditable]:focus) {
  outline: none;
  box-shadow: none;
}
</style>
