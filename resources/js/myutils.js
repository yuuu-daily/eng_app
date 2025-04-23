export default {
    getDateStr,
    getDateStrShort,
    getDateStrMid,
    getDateStrLong,
    getGradeStr,
    getFileNameFromUrl,
    getFileNameFromPath,
    getRandomStr,
    generatePassword,
    drawCourseName,
    drawCourseNames,
    removeTimestamp,
    getDateStr2
}

export function getDateStr(val) {
    if (!val) return '';
    return dayjs(val).format('YYYY年MM月DD日');
}

export function getDateStr2(val) {
    if (!val) return '';
    return dayjs(val).format('YYYY/MM/DD');
}

export function getDateStrShort(val) {
    if (!val) return '';
    return dayjs(val).format('MM/DD(ddd)');
}

export function getDateStrMid(val) {
    if (!val) return '';
    return dayjs(val).format('MM/DD(ddd) HH:mm');
}

export function getDateStrLong(val) {
    if (!val) return '';
    return dayjs(val).format('YYYY/MM/DD HH:mm');
}

export function getGradeStr(val) {
    if (!val) return '';
    const v = parseInt(val);
    switch (v) {
        case 7:
        case 8:
        case 9:
        case 10:
        case 11:
        case 12:
            return '小' + (v - 6);
        case 13:
        case 14:
        case 15:
            return '中' + (v - 12);
    }
}

export function getFileNameFromUrl(url) {
    const u = new URL(url)
    return u.pathname.split('/').pop();
}

export function getFileNameFromPath(path) {
    return path.split('/').pop();
}

export function getRandomStr(charset, len) {
    let ret = "";
    for (let i = 0, n = charset.length; i < len; ++i) {
        ret += charset.charAt(Math.floor(Math.random() * n));
    }
    return ret;
}

export function generatePassword() {
    let charset = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789";
    return getRandomStr(charset, 8)
}

export function drawCourseName(courseId, courses) {
    let ret = '';
    let cls = "badge ms-0 text-start ";
    let c = courses.find(v => v.id === parseInt(courseId));
    if (c) {
        cls = "my-badge-" + c.category_id;
        ret += "<span class='" + cls + "'>" + c.name + "</span>";
    }
    return ret;
}

export function drawCourseNames(courseIds, courses) {
    let ret = '';
    if (courseIds && courseIds.length > 0) {
        let a = courseIds.split(',');
        for (let i = 0; i < a.length; i++) {
            ret += drawCourseName(a[i], courses);
        }
    }
    return ret;
}

export function removeTimestamp(url) {
    const match = url.match(/\d{8}_\d{6}_(.*)/);
    if (match) {
        return match[1];
    } else if (url.includes('/storage/pdf')) {
        return getFileNameFromPath(url);
    } else {
        const parts = url.split('_');
        if (parts.length > 2) {
            return parts.slice(2).join('_');
        }
        return parts;
    }
}