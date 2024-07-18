<?php

namespace Laracord\Commands;

use Illuminate\Support\Str;
use Laracord\Commands\Contracts\Command as CommandContract;

abstract class Command extends AbstractCommand implements CommandContract
{
    /**
     * The command aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * The command cooldown.
     *
     * @var int
     */
    protected $cooldown = 0;

    /**
     * The command cooldown message.
     *
     * @var string
     */
    protected $cooldownMessage = '';

    /**
     * The command usage.
     *
     * @var string
     */
    protected $usage = '';

    /**
     * Maybe handle the command.
     *
     * @param  \Discord\Parts\Channel\Message  $message
     * @param  array  $args
     * @return mixed
     */
    public function maybeHandle($message, $args)
    {
        if (! $this->canDirectMessage() && ! $message->guild_id) {
            return;
        }

        if ($this->getGuild() && $message->guild_id !== $this->getGuild()) {
            return;
        }

        if (! $this->isAdminCommand()) {
            $this->handle($message, $args);

            return;
        }

        if ($this->isAdminCommand() && ! $this->isAdmin($message->author)) {
            return;
        }

        $this->handle($message, $args);
    }

    /**
     * Handle the command.
     *
     * @param  \Discord\Parts\Channel\Message  $message
     * @param  array  $args
     * @return void
     */
    abstract public function handle($message, $args);

    /**
     * Retrieve the command cooldown.
     *
     * @return int
     */
    public function getCooldown()
    {
        return $this->cooldown;
    }

    /**
     * Retrieve the command cooldown message.
     *
     * @return string
     */
    public function getCooldownMessage()
    {
        return $this->cooldownMessage;
    }

    /**
     * Retrieve the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return Str::start($this->getName(), $this->bot()->getPrefix());
    }

    /**
     * Retrieve the command usage.
     *
     * @return string
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * Retrieve the command aliases.
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }
}
