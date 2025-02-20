interface Job {
    jobId: number;
    title: string;
    description: string;
    salaryMin: number | null;
    salaryMax: number | null;
    locations: string[];
    employmentType: string | null;
}

interface Meta {
    current_page: number;
    entries_from: number;
    entries_to: number;
    entries_total: number;
}

interface JobsApiResponse {
    payload: Job[];
    meta: Meta;
}

export { Job, Meta, JobsApiResponse };