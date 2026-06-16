<?php $__env->startSection('content'); ?>
<div style="text-align: center; margin-top: 60px;">
    
    <div style="margin-bottom: 20px;">
        <span style="background: #e0e0e0; padding: 5px 15px; border-radius: 4px; font-size: 14px; color: #333;">
    <?php if($canEndRest): ?> 休憩中 
    <?php elseif($canEndWork || $canStartRest): ?> 出勤中
    <?php elseif(isset($attendance) && !is_null($attendance->end_time)): ?> 退勤済み  <?php else: ?> 勤務外 <?php endif; ?>
</span>
    </div>

    <div style="margin-bottom: 50px;">
        <p style="font-size: 18px; color: #555; margin: 0;"><?php echo e(now()->isoFormat('YYYY年MM月DD日(ddd)')); ?></p>
        <h1 style="font-size: 60px; font-weight: bold; margin: 10px 0;"><?php echo e(now()->format('H:i')); ?></h1>
        
        <?php if(isset($attendance) && !is_null($attendance->end_time)): ?>
            <p style="font-size: 20px; color: #333; margin-top: 20px;">お疲れ様でした。</p>
        <?php endif; ?>
    </div>

    <div style="display: flex; justify-content: center; gap: 20px;">
        
        <?php if($canStartWork): ?>
            <form action="/attendance/start" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" style="width: 200px; padding: 20px; background: #000; color: #fff; border: none; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">出勤</button>
            </form>
        <?php endif; ?>

        <?php if($canEndWork): ?>
            <form action="/attendance/end" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" style="width: 200px; padding: 20px; background: #000; color: #fff; border: none; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">退勤</button>
            </form>
        <?php endif; ?>

        <?php if($canStartRest): ?>
            <form action="/rest/start" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" style="width: 200px; padding: 20px; background: #fff; color: #000; border: 1px solid #000; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">休憩入</button>
            </form>
        <?php endif; ?>

        <?php if($canEndRest): ?>
            <form action="/rest/end" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" style="width: 200px; padding: 20px; background: #fff; color: #000; border: 1px solid #000; font-size: 20px; font-weight: bold; cursor: pointer; border-radius: 4px;">休憩戻</button>
            </form>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/index.blade.php ENDPATH**/ ?>