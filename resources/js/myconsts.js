export const GRADE = [
    // {id: '7', value: '小1'},
    // {id: '8', value: '小2'},
    // {id: '9', value: '小3'},
    // {id: '10', value: '小4'},
    // {id: '11', value: '小5'},
    // {id: '12', value: '小6'},
    {id: '13', value: '中1'},
    {id: '14', value: '中2'},
    {id: '15', value: '中3'},
];
export const ROLE_STUDENT = 0;
export const ROLE_TEACHER = 9;
export const ROLE_LECTURER = 99;
export const ROLE_ADMIN = 999;
export const COURSE_TYPES = [ { id: 'A', title: 'A' }, { id: 'B', title: 'B' }]

export const ADDRESS_OPTIONS = [
    { name: "北海道" },
    { name: "青森県" },
    { name: "岩手県" },
    { name: "宮城県" },
    { name: "秋田県" },
    { name: "山形県" },
    { name: "福島県" },
    { name: "茨城県" },
    { name: "栃木県" },
    { name: "群馬県" },
    { name: "埼玉県" },
    { name: "千葉県" },
    { name: "東京都" },
    { name: "神奈川県" },
    { name: "山梨県" },
    { name: "長野県" },
    { name: "新潟県" },
    { name: "富山県" },
    { name: "石川県" },
    { name: "福井県" },
    { name: "岐阜県" },
    { name: "静岡県" },
    { name: "愛知県" },
    { name: "三重県" },
    { name: "滋賀県" },
    { name: "京都府" },
    { name: "大阪府" },
    { name: "兵庫県" },
    { name: "奈良県" },
    { name: "和歌山県" },
    { name: "鳥取県" },
    { name: "島根県" },
    { name: "岡山県" },
    { name: "広島県" },
    { name: "山口県" },
    { name: "徳島県" },
    { name: "香川県" },
    { name: "愛媛県" },
    { name: "高知県" },
    { name: "福岡県" },
    { name: "佐賀県" },
    { name: "長崎県" },
    { name: "熊本県" },
    { name: "大分県" },
    { name: "宮崎県" },
    { name: "鹿児島県" },
    { name: "沖縄県" }
];

export const JUKU_OPTIONS = [{id: 0, name: '直営'}, {id: 1, name: 'FC'}, {id: 2, name: '外部'}];

export const LESSON_TIME = [{ id: 50, name: "50分授業" }, { id: 55, name: "55分授業" }, {id: 60, name: "60分授業"}, {id: 75, name: "75分授業"}];

export default {
    GRADE: GRADE,
    ROLE_STUDENT: ROLE_STUDENT,
    ROLE_TEACHER: ROLE_TEACHER,
    ROLE_LECTURER: ROLE_LECTURER,
    ROLE_ADMIN: ROLE_ADMIN,
    COURSE_TYPES: COURSE_TYPES,
    ADDRESS_OPTIONS: ADDRESS_OPTIONS,
    JUKU_OPTIONS: JUKU_OPTIONS,
    LESSON_TIME: LESSON_TIME,
}