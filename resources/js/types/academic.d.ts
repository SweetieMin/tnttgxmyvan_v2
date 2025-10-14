export interface AcademicYear {
    id: number;
    name: string;
    catechism_start_date: string;
    catechism_end_date: string;
    catechism_avg_score: number;
    catechism_training_score: number;
    activity_start_date: string;
    activity_end_date: string;
    activity_score: number;
    status_academic: string;
}

export interface Course {
    id: number;
    academic_year_id: number;
    ordering: number;
    name: string;
    description: string | null;
    academic_year?: AcademicYear;
    created_at: string;
    updated_at: string;
}

export interface Sector {
    id: number;
    academic_year_id: number;
    ordering: number;
    name: string;
    description: string | null;
    academic_year?: AcademicYear;
    created_at: string;
    updated_at: string;
}

export interface Role {
    id: number;
    name: string;
    description: string | null;
    ordering: number;
    created_at: string;
    updated_at: string;
    sub_roles?: Role[];
}

export interface Regulation {
    id: number;
    academic_year_id: number;
    ordering: number;
    description: string;
    type: 'plus' | 'minus';
    points: number;
    academic_year?: AcademicYear;
    created_at: string;
    updated_at: string;
}