<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import MyImageUploader from "@/Components/Commons/MyImageUploader.vue";
import MyButtonGoBack from "@/Components/Commons/MyButtonGoBack.vue";

const props = defineProps({
    // requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};

function openFile(url) {
    window.open(url, '_blank');
}

const form = useForm({
    id: 1,
    photo_url: null,
    photo_url_tmp: null,
    photo_file: null,
});

function submit() {
    if (form.photo_file) {
        uploadFile(form.photo_file);
    }
    // } else {
    //     upd();
    // }
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
    form.patch(route('user.setting.update', auth.id), {
        onSuccess: () => {
            toaster.success('保存しました');
            router.visit('/user/profile' );
        },
        onError: () => {
            toaster.error('保存に失敗しました');
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            プロフィール写真の変更
        </template>

        <template #description>
            プロフィール写真をアップロードして写真を変更することができます。
        </template>

        <template #content>
<!--            <h3 v-if="twoFactorEnabled && ! confirming" class="text-lg font-medium text-gray-900">-->
<!--                You have enabled two factor authentication.-->
<!--            </h3>-->

<!--            <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-gray-900">-->
<!--                Finish enabling two factor authentication.-->
<!--            </h3>-->

<!--            <h3 v-else class="text-lg font-medium text-gray-900">-->
<!--                You have not enabled two factor authentication.-->
<!--            </h3>-->

<!--            <div class="mt-3 max-w-xl text-sm text-gray-600">-->
<!--                <p>-->
<!--                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.-->
<!--                </p>-->
<!--            </div>-->

<!--            <div v-if="twoFactorEnabled">-->
<!--                <div v-if="qrCode">-->
<!--                    <div class="mt-4 max-w-xl text-sm text-gray-600">-->
<!--                        <p v-if="confirming" class="font-semibold">-->
<!--                            To finish enabling two factor authentication, scan the following QR code using your phone's authenticator application or enter the setup key and provide the generated OTP code.-->
<!--                        </p>-->

<!--                        <p v-else>-->
<!--                            Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application or enter the setup key.-->
<!--                        </p>-->
<!--                    </div>-->

<!--                    <div class="mt-4 p-2 inline-block bg-white" v-html="qrCode" />-->

<!--                    <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-gray-600">-->
<!--                        <p class="font-semibold">-->
<!--                            Setup Key: <span v-html="setupKey"></span>-->
<!--                        </p>-->
<!--                    </div>-->

<!--                    <div v-if="confirming" class="mt-4">-->
<!--                        <InputLabel for="code" value="Code" />-->

<!--                        <TextInput-->
<!--                            id="code"-->
<!--                            v-model="confirmationForm.code"-->
<!--                            type="text"-->
<!--                            name="code"-->
<!--                            class="block mt-1 w-1/2"-->
<!--                            inputmode="numeric"-->
<!--                            autofocus-->
<!--                            autocomplete="one-time-code"-->
<!--                            @keyup.enter="confirmTwoFactorAuthentication"-->
<!--                        />-->

<!--                        <InputError :message="confirmationForm.errors.code" class="mt-2" />-->
<!--                    </div>-->
<!--                </div>-->

<!--                <div v-if="recoveryCodes.length > 0 && ! confirming">-->
<!--                    <div class="mt-4 max-w-xl text-sm text-gray-600">-->
<!--                        <p class="font-semibold">-->
<!--                            Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.-->
<!--                        </p>-->
<!--                    </div>-->

<!--                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">-->
<!--                        <div v-for="code in recoveryCodes" :key="code">-->
<!--                            {{ code }}-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="mr-4 flex-shrink-0 mb-4">
            <div class="grid grid-cols-2 gap-4">
<!--                <div>-->
                    <!-- <MyImageUploader :maxSize="8000" title="写真" :imgUrl="form.photo_url" v-model:imgFile="form.photo_file"/> -->
                    <MyImageUploader :maxSize="8000" title="写真" v-model:imgFile="form.photo_file"/>
<!--                </div>-->
                <!-- <div v-if="props.photo_url" class="py-2 px-6 w-full link-primary">
                    <span
                        @click="openFile(props.photo_url)"
                        class="cursor-pointer text-blue-600 hover:underline"
                    >{{ props.short_url }}</span>
                </div> -->
            </div>
            </div>

            <!-- <div class="mt-5">
                <div v-if="! twoFactorEnabled">
                    <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <PrimaryButton type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
                            Enable
                        </PrimaryButton>
                    </ConfirmsPassword>
                </div>

                <div v-else>
                    <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <PrimaryButton
                            v-if="confirming"
                            type="button"
                            class="me-3"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                        >
                            Confirm
                        </PrimaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && ! confirming"
                            class="me-3"
                        >
                            Regenerate Recovery Codes
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && ! confirming"
                            class="me-3"
                        >
                            Show Recovery Codes
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <SecondaryButton
                            v-if="confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Cancel
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <DangerButton
                            v-if="! confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Disable
                        </DangerButton>
                    </ConfirmsPassword>
                </div>
            </div> -->
            <div>
                <PrimaryButton class="mt-3 me-3" :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing" @click="submit()">
                    保存
                </PrimaryButton>
                <MyButtonGoBack :target="route('user.post')"/>
            </div>
        </template>
    </ActionSection>
</template>
