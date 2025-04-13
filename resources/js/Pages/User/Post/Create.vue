<template>
    <!-- <AppLayout>
    <div v-if="editor" class="container">
      <div class="control-group">
        <div class="button-group">
            <div class="button-group flex flex-wrap gap-2 mb-4">
  <button
    @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
    :class="[
      editor.isActive('heading', { level: 1 }) ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    H1
  </button>

  <button
    @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
    :class="[
      editor.isActive('heading', { level: 2 }) ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    H2
  </button>

  <button
    @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
    :class="[
      editor.isActive('heading', { level: 3 }) ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    H3
  </button>

  <button
    @click="editor.chain().focus().setParagraph().run()"
    :class="[
      editor.isActive('paragraph') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    Paragraph
  </button>

  <button
    @click="editor.chain().focus().toggleBold().run()"
    :class="[
      editor.isActive('bold') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    太字
  </button>

  <button
    @click="editor.chain().focus().toggleItalic().run()"
    :class="[
      editor.isActive('italic') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    Italic
  </button>

  <button
    @click="editor.chain().focus().toggleStrike().run()"
    :class="[
      editor.isActive('strike') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    取り消し線
  </button>

  <button
    @click="editor.chain().focus().toggleHighlight().run()"
    :class="[
      editor.isActive('highlight') ? 'bg-yellow-400 text-black border-yellow-400' : 'bg-gray-100 text-black',
      'px-3 py-1 border rounded transition-all'
    ]"
  >
    Highlight
  </button>
  <button
  @click="editor.chain().focus().toggleOrderedList().run()"
  :class="[
    editor.isActive('orderedList') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
    'px-3 py-1 border rounded transition-all'
  ]"
>
  Ordered list
</button>

<button
  @click="editor.chain().focus().toggleCodeBlock().run()"
  :class="[
    editor.isActive('codeBlock') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
    'px-3 py-1 border rounded transition-all'
  ]"
>
  Code block
</button>

<button
  @click="editor.chain().focus().toggleBlockquote().run()"
  :class="[
    editor.isActive('blockquote') ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-black',
    'px-3 py-1 border rounded transition-all'
  ]"
>
  Blockquote
</button>

<button
  @click="editor.chain().focus().setHorizontalRule().run()"
  class="bg-gray-100 text-black px-3 py-1 border rounded transition-all"
>
  Horizontal rule
</button>

</div>

        </div>
      </div>
      <editor-content :editor="editor" class="border rounded tiptap prose"/>
    </div>
    </AppLayout> -->
    <AppLayout>
    <div v-if="editor" class="container p-4">
      <!-- Toolbar -->
      <div class="flex gap-2 mb-4">
        <button @click="showMenu = !showMenu" class="px-2 py-1 border rounded hover:bg-gray-100">
          ＋
        </button>

        <!-- ポップアップメニュー -->
        <div
          v-if="showMenu"
          class="absolute z-10 mt-10 w-48 bg-white border rounded shadow-lg"
        >
          <ul class="text-sm">
            <li>
              <button @click="setBlock('heading', { level: 1 })" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                H1
              </button>
            </li>
            <li>
              <button @click="setBlock('heading', { level: 2 })" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                H2
              </button>
            </li>
            <li>
              <button @click="setBlock('highlight')" class="block w-full text-left px-4 py-2 hover:bg-yellow-100">Highlight</button>
            </li>
            <li>
              <button @click="setBlock('orderedList')" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                Ordered List
              </button>
            </li>
            <li>
              <button @click="setBlock('codeBlock')" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                Code Block
              </button>
            </li>
            <li>
              <button @click="setBlock('blockquote')" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                Blockquote
              </button>
            </li>
            <li>
              <button @click="setBlock('horizontalRule')" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                Divider
              </button>
            </li>
          </ul>
        </div>
      </div>

      <!-- エディタ -->
      <editor-content
  :editor="editor"
  class="tiptap w-full rounded p-4 border focus-within:ring-2 focus-within:ring-blue-400 caret-blue-600"
/>
    </div>
    <div class="mr-4 flex-shrink-0 mb-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <MyImageUploader :maxSize="8000" title="写真" :imgUrl="form.photo_url" v-model:imgFile="form.photo_file"/>
            </div>
            <div>
                <InputLabel for="name" value="写真(申請)"/>
                <img :src="form.photo_url_tmp">
            </div>
        </div>
    </div>
    <div>
                <PrimaryButton class="mt-3 me-3" :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing" @click="submit()">
                    保存
                </PrimaryButton>
                <MyButtonGoBack :target="route('post.index')"/>
            </div>
  </AppLayout>
</template>
  
<script setup>
  // import { ref, onMounted, onBeforeUnmount } from 'vue'
  // import { Editor, EditorContent } from '@tiptap/vue-3'
  // import StarterKit from '@tiptap/starter-kit'
  // import Highlight from '@tiptap/extension-highlight'
  // import TextAlign from '@tiptap/extension-text-align'
  // import AppLayout from '@/Layouts/User/AppLayout.vue'

  // const editor = ref(null)
  
  // onMounted(() => {
  //   editor.value = new Editor({
  //     extensions: [
  //       StarterKit,
  //       Highlight,
  //       TextAlign.configure({
  //         types: ['heading', 'paragraph'],
  //       }),
  //     ],
  //     content: '',
  //     editorProps: {
  //       attributes: {
  //           class: 'tiptap',
  //       },
  //     },
  //   })
  // })
  
  // onBeforeUnmount(() => {
  //   editor.value?.destroy()
  // })
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Highlight from '@tiptap/extension-highlight'
import TextAlign from '@tiptap/extension-text-align'
import AppLayout from '@/Layouts/User/AppLayout.vue'
import MyImageUploader from "@/Components/Commons/MyImageUploader.vue"
import {router, useForm, usePage} from '@inertiajs/vue3'
import InputLabel from "@/Components/InputLabel.vue"
import MyButtonGoBack from "@/Components/Commons/MyButtonGoBack.vue"
import PrimaryButton from "@/Components/Commons/PrimaryButton.vue";

const props = defineProps({});
const auth = usePage().props.auth.user;

const editor = ref(null)
const showMenu = ref(false)

onMounted(() => {
  editor.value = new Editor({
    extensions: [
      StarterKit,
      Highlight,
      TextAlign.configure({
        types: ['heading', 'paragraph'],
      }),
    ],
    content: '',
    editorProps: {
      attributes: {
        class: 'tiptap',
      },
    },
  })
})

const form = useForm({
    id: 1,
    photo_url: null,
    photo_url_tmp: null,
    photo_file: null,
});

function submit() {
    if (form.photo_file) {
        uploadFile(form.photo_file);
    } else {
        upd();
    }
}

function uploadFile(file) {
    let params = new URLSearchParams();
    const ts = dayjs().format('YYMMDDHHmmss');
    params.append('filename', 'j' + ts + '.' + file.name.split('.').pop()); //キャッシュ回避のため
    params.append('dir', 'post/' + 1 + '/upload/');
    params.append('type', file.type);
    axios.post('/util/get_presignedurl', params).then(function (res1) {
        const axiosOptions = {headers: {'Content-Type': file.type}};
        if (res1.status === 200) {
            axios.put(res1.data.preSignedUrl, file, axiosOptions).then(function (res2) {
                form.photo_file = null; //用済み(これでいいか微妙)
                // form.photo_url = res1.data.s3key; //更新するCDNのURL
                form.photo_url = res1.data.preSignedUrl; //更新するCDNのURL
                // console.log(res1.data.preSignedUrl);
                upd();
            }).catch(function (e) {
                toaster.error('保存に失敗しました(2)');
                console.log(e);
            });
        }
    }).catch(function (e) {
        toaster.error('保存に失敗しました(3)');
        console.log(e);
    });
}

const upd = () => {
    form.patch(route('post.update', auth.id), {
        onSuccess: () => {
            toaster.success('保存しました');
            router.visit('/post/create' );
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
  }

  showMenu.value = false
}
</script>

<!-- <style scoped>
/* List styles */
::v-deep(.tiptap p) {
  line-height: 1.5 !important;
  margin-top: 0.5em !important;
  margin-bottom: 0.5em !important;
}

::v-deep(.tiptap h1) {
  line-height: 1.2;
  margin-top: 0.5em;
  margin-bottom: 0.5em;
  font-size: 1.5rem;
}

::v-deep(.tiptap h2) {
  line-height: 1.2;
  margin-top: 0.5em;
  margin-bottom: 0.5em;
  font-size: 1.25rem;
}

::v-deep(.tiptap ul),
::v-deep(.tiptap ol) {
  margin: 0.75rem 0;
  padding-left: 1.25rem;
  line-height: 1.5;
}

/* 通常のインライン code */
::v-deep(.tiptap code) {
  background-color: var(--purple-light);
  border-radius: 0.4rem;
  color: var(--black);
  font-size: 0.85rem;
  padding: 0.25em 0.3em;
}

/* コードブロック（codeBlock） */
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
  text-indent: 0;
}

::v-deep(.tiptap pre code) {
  background: none;
  color: inherit;
  padding: 0;
  font-size: inherit;
}

/* blockquote */
::v-deep(.tiptap blockquote) {
  quotes: none;
  border-left: 4px solid #cbd5e1;
  padding-left: 1rem;
  margin: 1.5rem 0;
  color: #475569;
  font-style: italic;
  background-color: #f8fafc;
}

::v-deep(.tiptap blockquote::before),
::v-deep(.tiptap blockquote::after) {
  content: none;
}
</style> -->
<style scoped>
/* 見出し */
::v-deep(.tiptap h1) {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 1.25rem 0 0.75rem;
}

::v-deep(.tiptap h2) {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 1rem 0 0.5rem;
}

::v-deep(.tiptap h3) {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0.75rem 0 0.5rem;
}

/* 段落 */
::v-deep(.tiptap p) {
  margin: 0.5rem 0;
  line-height: 1.6;
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
</style>