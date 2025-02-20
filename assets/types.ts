interface Job {
    jobId: number;
    title: string;
    description: string;
    salaryMin: number | null;
    salaryMax: number | null;
    locations: string[];
    employmentType: string | null;
    currency: string | null;
}

interface Meta {
    entriesFrom: number;
    entriesTo: number;
    entriesTotal: number;
}

interface JobsApiResponse {
    jobs: Job[];
    meta: Meta;
}

export { Job, Meta, JobsApiResponse };