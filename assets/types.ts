interface Job {
    job_id: number;
    title: string;
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