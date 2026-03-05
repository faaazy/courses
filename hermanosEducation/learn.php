<?php
require_once 'connect.php';
require_once 'functions.php';

redirectLogin();
//$courseId = (int)($_GET['id']     ?? 0);
//$lessonId = (int)($_GET['lesson'] ?? 1);
//if ($courseId === 0) redirect(base_url('/index.php'));
//
//$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
//$stmt->execute([$courseId]);
//$course = $stmt->fetch();
//if (!$course) redirect(base_url('/index.php'));
//
//$stmt = $conn->prepare(
//    "SELECT o.expires_at FROM orders o
//     WHERE o.client_id = ? AND o.course_id = ? AND o.expires_at >= CURDATE()
//     LIMIT 1"
//);
//$stmt->execute([$_SESSION['client_id'], $courseId]);
//$order = $stmt->fetch();
//if (!$order) redirect(base_url('/course.php?id=') . $courseId);
//
//$modules = [
//    [
//        'title'   => 'Введение в курс',
//        'lessons' => [
//            ['id' => 1,  'title' => 'Добро пожаловать на курс',          'duration' => '5:30',  'type' => 'video'],
//            ['id' => 2,  'title' => 'Что вы узнаете',                    'duration' => '3:15',  'type' => 'video'],
//            ['id' => 3,  'title' => 'Необходимые инструменты',           'duration' => '8:40',  'type' => 'video'],
//        ],
//    ],
//    [
//        'title'   => 'Основы и теория',
//        'lessons' => [
//            ['id' => 4,  'title' => 'Ключевые концепции',                'duration' => '12:20', 'type' => 'video'],
//            ['id' => 5,  'title' => 'Практическое применение',           'duration' => '15:05', 'type' => 'video'],
//            ['id' => 6,  'title' => 'Проверочный тест',                  'duration' => '10 вопросов', 'type' => 'quiz'],
//        ],
//    ],
//    [
//        'title'   => 'Продвинутые техники',
//        'lessons' => [
//            ['id' => 7,  'title' => 'Углублённый разбор',                'duration' => '18:50', 'type' => 'video'],
//            ['id' => 8,  'title' => 'Разбор реальных кейсов',            'duration' => '22:10', 'type' => 'video'],
//            ['id' => 9,  'title' => 'Практическое задание',              'duration' => '~30 мин', 'type' => 'task'],
//        ],
//    ],
//    [
//        'title'   => 'Финальный проект',
//        'lessons' => [
//            ['id' => 10, 'title' => 'Постановка задачи',                 'duration' => '7:00',  'type' => 'video'],
//            ['id' => 11, 'title' => 'Выполнение проекта',                'duration' => '~60 мин', 'type' => 'task'],
//            ['id' => 12, 'title' => 'Поздравляем с завершением курса!',  'duration' => '2:45',  'type' => 'video'],
//        ],
//    ],
//];
//
//$currentLesson = null;
//foreach ($modules as $module) {
//    foreach ($module['lessons'] as $lesson) {
//        if ($lesson['id'] === $lessonId) {
//            $currentLesson = $lesson;
//            break 2;
//        }
//    }
//}
//if (!$currentLesson) {
//    $currentLesson = $modules[0]['lessons'][0];
//    $lessonId      = 1;
//}
//
//$typeIcon  = ['video' => '▶', 'quiz' => '📝', 'task' => '✏️'];
//$typeLabel = ['video' => 'Видео', 'quiz' => 'Тест', 'task' => 'Задание'];

require_once 'views/header.php';
?>

<div class="process">
    <h2 class="learn-player__stub-title">Разработка данного блока в процессе...</h2>
</div>

<!--    <div class="learn-layout">-->
<!---->
<!--        <aside class="learn-sidebar">-->
<!--            <div class="learn-sidebar__header">-->
<!--                <a href="--><?php //= base_url('/course.php?id=' . $courseId) ?><!--" class="back-link" style="margin-bottom:0;">← К курсу</a>-->
<!--                <div class="learn-sidebar__title">--><?php //= htmlspecialchars($course['title']) ?><!--</div>-->
<!--                <div class="learn-sidebar__access">-->
<!--                    Доступ до --><?php //= date('d.m.Y', strtotime($order['expires_at'])) ?>
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="learn-sidebar__modules">-->
<!--                --><?php //foreach ($modules as $mIdx => $module): ?>
<!--                    <div class="learn-module">-->
<!--                        <div class="learn-module__title">-->
<!--                            --><?php //= $mIdx + 1 ?><!--. --><?php //= htmlspecialchars($module['title']) ?>
<!--                        </div>-->
<!--                        --><?php //foreach ($module['lessons'] as $lesson):
//                            $isActive = $lesson['id'] === $lessonId;
//                            ?>
<!--                            <a href="--><?php //= base_url('/learn.php?id=' . $courseId) ?><!--&lesson=--><?php //= $lesson['id'] ?><!--"-->
<!--                               class="learn-lesson --><?php //= $isActive ? 'learn-lesson--active' : '' ?><!--">-->
<!--                                <span class="learn-lesson__icon">--><?php //= $typeIcon[$lesson['type']] ?><!--</span>-->
<!--                                <span class="learn-lesson__text">-->
<!--                                <span class="learn-lesson__name">--><?php //= htmlspecialchars($lesson['title']) ?><!--</span>-->
<!--                                <span class="learn-lesson__meta">--><?php //= htmlspecialchars($typeLabel[$lesson['type']]) ?><!-- · --><?php //= htmlspecialchars($lesson['duration']) ?><!--</span>-->
<!--                            </span>-->
<!--                            </a>-->
<!--                        --><?php //endforeach; ?>
<!--                    </div>-->
<!--                --><?php //endforeach; ?>
<!--            </div>-->
<!--        </aside>-->
<!---->
<!--        <main class="learn-main">-->
<!---->
<!--            --><?php //if ($currentLesson['type'] === 'video'): ?>
<!---->
<!--                <div class="learn-player">-->
<!--                    <div class="learn-player__stub">-->
<!--                        <div class="learn-player__play">▶</div>-->
<!--                        <div class="learn-player__stub-title">--><?php //= htmlspecialchars($currentLesson['title']) ?><!--</div>-->
<!--                        <div class="learn-player__stub-sub">--><?php //= htmlspecialchars($course['title']) ?><!--</div>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            --><?php //elseif ($currentLesson['type'] === 'quiz'): ?>
<!---->
<!--                <div class="learn-quiz">-->
<!--                    <h2>📝 --><?php //= htmlspecialchars($currentLesson['title']) ?><!--</h2>-->
<!--                    <p style="color:var(--text-muted); margin-bottom:28px;">Ответьте на вопросы чтобы проверить свои знания</p>-->
<!---->
<!--                    --><?php
//                    $questions = [
//                        ['q' => 'Что является основным преимуществом изученного подхода?',  'answers' => ['Простота реализации', 'Высокая производительность', 'Низкая стоимость', 'Все перечисленные'], 'correct' => 3],
//                        ['q' => 'Какой инструмент чаще всего применяется на практике?',      'answers' => ['Инструмент A', 'Инструмент B', 'Инструмент C', 'Инструмент D'],                          'correct' => 1],
//                        ['q' => 'Какой шаг выполняется первым в изученном процессе?',        'answers' => ['Анализ данных', 'Постановка задачи', 'Реализация', 'Тестирование'],                      'correct' => 1],
//                    ];
//                    ?>
<!---->
<!--                    <form class="quiz-form" onsubmit="submitQuiz(event)">-->
<!--                        --><?php //foreach ($questions as $qIdx => $q): ?>
<!--                            <div class="quiz-question">-->
<!--                                <div class="quiz-question__text">--><?php //= $qIdx + 1 ?><!--. --><?php //= htmlspecialchars($q['q']) ?><!--</div>-->
<!--                                <div class="quiz-answers">-->
<!--                                    --><?php //foreach ($q['answers'] as $aIdx => $answer): ?>
<!--                                        <label class="quiz-answer">-->
<!--                                            <input type="radio" name="q--><?php //= $qIdx ?><!--" value="--><?php //= $aIdx ?><!--">-->
<!--                                            <span>--><?php //= htmlspecialchars($answer) ?><!--</span>-->
<!--                                        </label>-->
<!--                                    --><?php //endforeach; ?>
<!--                                </div>-->
<!--                            </div>-->
<!--                        --><?php //endforeach; ?>
<!--                        <button type="submit" class="btn btn-gold btn-lg">Проверить ответы</button>-->
<!--                    </form>-->
<!---->
<!--                    <div class="quiz-result" id="quizResult" style="display:none;"></div>-->
<!--                </div>-->
<!---->
<!--            --><?php //elseif ($currentLesson['type'] === 'task'): ?>
<!---->
<!--                <div class="learn-task">-->
<!--                    <h2>✏️ --><?php //= htmlspecialchars($currentLesson['title']) ?><!--</h2>-->
<!--                    <div class="learn-task__desc">-->
<!--                        <h3>Описание задания</h3>-->
<!--                        <p>-->
<!--                            На основе материалов из предыдущих уроков выполните практическое задание.-->
<!--                            Примените полученные знания для решения реальной задачи из области-->
<!--                            «--><?php //= htmlspecialchars($course['title']) ?><!--».-->
<!--                        </p>-->
<!--                        <h3 style="margin-top:20px;">Шаги выполнения</h3>-->
<!--                        <ol style="padding-left:20px; line-height:2;">-->
<!--                            <li>Изучите постановку задачи</li>-->
<!--                            <li>Составьте план решения</li>-->
<!--                            <li>Реализуйте решение используя изученные инструменты</li>-->
<!--                            <li>Проверьте результат и сделайте выводы</li>-->
<!--                        </ol>-->
<!--                        <h3 style="margin-top:20px;">Критерии оценки</h3>-->
<!--                        <ul style="padding-left:20px; line-height:2;">-->
<!--                            <li>Правильность применения концепций</li>-->
<!--                            <li>Качество реализации</li>-->
<!--                            <li>Полнота решения</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <div style="margin-top:28px;">-->
<!--                        <button class="btn btn-gold btn-lg" onclick="alert('Задание сдано! В реальном приложении здесь была бы загрузка файла или форма.')">-->
<!--                            Сдать задание-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            --><?php //endif; ?>
<!---->
<!--            --><?php
//            // Найти предыдущий и следующий урок
//            $allLessons = array_merge(...array_column($modules, 'lessons'));
//            $currentIdx = array_search($lessonId, array_column($allLessons, 'id'));
//            $prevLesson = $currentIdx > 0                       ? $allLessons[$currentIdx - 1] : null;
//            $nextLesson = $currentIdx < count($allLessons) - 1  ? $allLessons[$currentIdx + 1] : null;
//            ?>
<!--            <div class="learn-nav">-->
<!--                --><?php //if ($prevLesson): ?>
<!--                    <a href="--><?php //= base_url('/learn.php?id=' . $courseId) ?><!--&lesson=--><?php //= $prevLesson['id'] ?><!--" class="btn btn-outline">-->
<!--                        ← --><?php //= htmlspecialchars($prevLesson['title']) ?>
<!--                    </a>-->
<!--                --><?php //else: ?>
<!--                    <div></div>-->
<!--                --><?php //endif; ?>
<!---->
<!--                --><?php //if ($nextLesson): ?>
<!--                    <a href="--><?php //= base_url('/learn.php?id=' . $courseId) ?><!--&lesson=--><?php //= $nextLesson['id'] ?><!--" class="btn btn-gold">-->
<!--                        --><?php //= htmlspecialchars($nextLesson['title']) ?><!-- →-->
<!--                    </a>-->
<!--                --><?php //endif; ?>
<!--            </div>-->
<!---->
<!--        </main>-->
<!--    </div>-->
<!---->
<!--    <script>-->
<!--        function submitQuiz(e) {-->
<!--            e.preventDefault();-->
<!--            const correct = [3, 1, 1];-->
<!--            let score = 0;-->
<!--            correct.forEach((ans, i) => {-->
<!--                const selected = document.querySelector(`input[name="q${i}"]:checked`);-->
<!--                if (selected && parseInt(selected.value) === ans) score++;-->
<!--            });-->
<!--            const result = document.getElementById('quizResult');-->
<!--            result.style.display = 'block';-->
<!--            if (score === correct.length) {-->
<!--                result.className = 'quiz-result alert alert-success';-->
<!--                result.innerHTML = `✓ Отлично! Все ${score} из ${correct.length} ответов верны!`;-->
<!--            } else {-->
<!--                result.className = 'quiz-result alert alert-error';-->
<!--                result.innerHTML = `Вы ответили верно на ${score} из ${correct.length} вопросов. Попробуйте ещё раз.`;-->
<!--            }-->
<!--            result.scrollIntoView({ behavior: 'smooth', block: 'center' });-->
<!--        }-->
<!--    </script>-->

<?php require_once 'views/footer.php'; ?>