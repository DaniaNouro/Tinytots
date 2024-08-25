<?php
namespace App\Services\ParentService;


class ComplaintService{

    public function submitComplaint(Request $request)
    {
        $request->validate([
            'complaint_text' => 'required',

        ]);

        $complaint = Complaint::create($request->all());

        return response()->json(['message' => 'شكواك تم إرسالها بنجاح']);
    }
/*___________________________________________________________________________*/
    public function index()
    {
        $complaints = Complaint::all();
        return view('admin.complaints.index', compact('complaints'));
    }
/*___________________________________________________________________________*/

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        return view('admin.complaints.show', compact('complaint'));
    }
/*___________________________________________________________________________*/
    public function updateStatus(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->status = $request->status;
        $complaint->save();

        return response()->json(['message' => 'تم تحديث حالة الشكوى بنجاح']);
    }
}

/*تنفيذ ما طلبته، يمكنك إنشاء مجموعة من الـ Services والـ Controllers لتنفيذ كل وظيفة بشكل منفصل. هنا هي الأسماء المقترحة للـ Services والـ Controllers:
Services

    StudentService.php
        هذه الـ Service ستكون مسؤولة عن جميع العمليات المتعلقة بالطلاب.

    ParentProfileService.php
        هذه الـ Service ستكون مسؤولة عن استرجاع معلومات ملف الشخصي للأهل.

    EvaluationService.php
        هذه الـ Service ستكون مسؤولة عن استرجاع تقييمات الأولاد (الإيجابية والسلبية).

    AbsenceService.php
        هذه الـ Service ستكون مسؤولة عن استرجاع معلومات الغيابات للأولاد.

Controllers

    ParentController.php
        هذا الـ Controller سيدير جميع الواجهات الخاصة بعرض بيانات الأهل والوصول إلى ما يتعلق بأبنائهم.

التفاصيل الدقيقة لكل Service و Controller:
StudentService.php

    الدوال المقترحة:
        getStudentByParentId($parentId): لاسترجاع قائمة بالطلاب المرتبطين بأحد الأهل.
        getStudentProfile($studentId): لاسترجاع معلومات ملف الشخصي لطالب معين.
        getStudentEvaluations($studentId): لاسترجاع تقييمات الطالب (الإيجابية والسلبية).
        getStudentAbsences($studentId): لاسترجاع معلومات الغيابات للطالب.

ParentProfileService.php

    الدوال المقترحة:
        getParentProfile($parentId): لاسترجاع معلومات ملف الشخصي للأب أو الأم.
        updateParentProfile($parentId, $data): لتحديث*/ 












}
