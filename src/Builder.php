<?php

namespace BeeJeeMVC;

class Builder
{
	private $controller;
    private $base;

	public function __construct($name, $base)
	{
		$this->controller = $name;
        $this->base = $base;
	}

    /**
     * @param $arrayTask
     *
     * @return string
     */
	public function buildList($arrayTask): string
    {
		$content = '';

		foreach ($arrayTask as $task) {
			$content .= $this->buildTask($task);
		}

		return $content;
	}

    /**
     * @param Task $task
     *
     * @return string
     */
	public function buildTask(Task $task): string
    {
		$content =
			'<div id="$task->getHash()" class="task">
				<a href="' . $this->base . strtolower($this->controller) . '/edit/' . $task->getHash() . '">
					<?php echo $task->getHash(); ?>
				</a>
				<div class="userName">' . $task->getUserName() . '</div>
				<div class="email">' . $task->getEmail() . '</div>
				<div class="text">' . $task->getText() . '</div>
				<div class="status">' . $task->getStatus() . '</div>
			</div>';

		return $content;
	}
}
